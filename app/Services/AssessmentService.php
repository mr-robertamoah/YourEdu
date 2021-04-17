<?php

namespace App\Services;

use App\DTOs\AssessmentDTO;
use App\DTOs\DiscussionDTO;
use App\Events\DeleteAssessmentEvent;
use App\Events\NewAssessmentEvent;
use App\Events\UpdateAssessmentEvent;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\AssessmentException;
use App\Http\Resources\AssessmentMiniResource;
use App\Http\Resources\AssessmentResource;
use App\Http\Resources\DashboardItemMiniResource;
use App\Notifications\AssessmentNotification;
use App\Traits\ServiceTrait;
use App\User;
use App\YourEdu\Assessment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class AssessmentService
{
    use ServiceTrait;

    const VALIDANSWERTYPES = [
        'TRUE_FALSE', 'SHORT_ANSWER', 'LONG_ANSWER', 'IMAGE', 'VIDEO',
        'AUDIO', 'OPTION', 'NUMBER', 'ARRANGE', 'FLOW', 'FILE'
    ];
    
    public static function getAnswerType($answerType)
    {
        if (!strlen($answerType)) {
            return 'SHORT_ANSWER';
        }

        if (str_contains(strtolower($answerType),'short')) {
            return 'SHORT_ANSWER';
        }

        if (str_contains(strtolower($answerType),'long')) {
            return 'LONG_ANSWER';
        }

        if (str_contains(strtolower($answerType),'true')) {
            return 'TRUE_FALSE';
        }

        if (!in_array(strtoupper($answerType), self::VALIDANSWERTYPES)) {
            return 'SHORT_ANSWER';
        }

        return strtoupper($answerType);
    }

    public function createAssessment
    (
        AssessmentDTO $assessmentDTO
    ): Assessment
    {
        try {
            DB::beginTransaction();

            $assessmentDTO->addedby = $this->getModel(
                $assessmentDTO->account,
                $assessmentDTO->accountId
            );

            $this->checkRequiredData(
                assessmentDTO: $assessmentDTO
            );

            $assessment = $this->createOrUpdateAssessment($assessmentDTO, 'create');

            $this->comparePublishedAndDueDates($assessment, $assessmentDTO);

            $assessment = $this->addAssessmentSections($assessment,$assessmentDTO);

            $assessment = $this->attachAssessmentToItems($assessment,$assessmentDTO);

            $this->checkAssessmentSections($assessment);
            
            $assessment = $this->createAutoDiscussion($assessment, $assessmentDTO);

            $this->notifyAttachedItemsAccounts(
                $assessment,
                "am assessment with name: {$assessment->name} has been added.",
            );

            $assessmentDTO->methodType = 'created';
            $this->broadcastAssessment($assessment, $assessmentDTO);

            return $assessment;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            $this->throwAssessmentException(
                message: "oops! something happened",
                data: $assessmentDTO
            );
        }
    }

    private function comparePublishedAndDueDates
    (
        Assessment $assessment,
        AssessmentDTO $assessmentDTO
    )
    {
        if (!$assessment->publised_at || !$assessment->due_at) {
            return;
        }

        if ($assessment->published_at->diffInDays(
            $assessment->due_at
        ) >= 1) {
            return;
        }

        $this->throwAssessmentException(
            message: "due date for the assessment with name, {$assessment->name}, should be at least a day after the published date",
            data: $assessmentDTO
        );
    }

    private function checkRequiredData
    (
        Assessment $assessment = null,
        AssessmentDTO $assessmentDTO
    )
    {
        if (count($assessmentDTO->assessmentSections)) {
            return;
        }

        if ($assessment?->notRemovingAllSections($assessmentDTO->removedAssessmentSections)) {
            return;
        }

        $this->throwAssessmentException(
            message: "a assessment requires at least one section",
            data: $assessmentDTO
        );
    }

    private function notifyAttachedItemsAccounts
    (
        Assessment $assessment,
        string $message,
        $authority = false,
        $detachedItems = [],
        $onlyDetached = false,
    ) : void
    {
        $userIds = [];
        
        if (!$onlyDetached) {
            $userIds = $this->getItemsUserIds(
                assessment: $assessment,
                authority: $authority
            );
        }

        ray($detachedItems)->green();
        foreach ($detachedItems as $item) {
            array_push($userIds,...$item->getAuthorizedUserIds($authority));
        }
        
        $this->notifyUsers(
            users: User::whereIn('id',$userIds)->get(),
            message: $message,
            assessment: $assessment
        );
    }

    private function detachAssessmentFromAllItems
    (
        Assessment $assessment,
        AssessmentDTO $assessmentDTO
    ) : array
    {
        $detachedItems = $assessment->items()->unique();
        
        $assessment->assessmentables->each(
            function($assessmentable) use ($assessmentDTO) {
            
                $this->checkCanAttachOrDetachItem(
                    $assessmentable->assessmentable,
                    $assessmentDTO,
                    $assessmentable->itemable,
                );

                $assessmentable->delete();
            }
        );
        
        return [$assessment->refresh(), $detachedItems];
    }

    private function getItemsUserIds
    (
        Assessment $assessment, 
        bool $authority = false
    ) : array
    {
        $userIds = [];
        
        foreach ($assessment->allItems() as $item) {
            array_push($userIds,...$item->getAuthorizedUserIds($authority));
        }

        return array_unique($userIds);
    }

    private function notifyUsers
    (
        Collection $users,
        $message,
        Assessment $assessment = null
    )
    {
        Notification::send(
            $users,
            new AssessmentNotification(
                message: $message,
                assessmentResource: new AssessmentResource($assessment)
            )
        );
    }

    public function attachAssessmentToItems
    (
        Assessment $assessment,
        AssessmentDTO $assessmentDTO
    ) : Assessment
    {
        foreach ($assessmentDTO->attachedItems as $attachedItemDTO) {
            $assessmentable = $this->getModel($attachedItemDTO->item, $attachedItemDTO->itemId);

            $itemable = $this->getItemable($attachedItemDTO, $assessmentable);

            $this->checkCanAttachOrDetachItem(
                $assessmentable, 
                $assessmentDTO,
                $attachedItemDTO
            );

            $this->checkExistenceOfAssessment(
                $assessment, $assessmentable, $itemable, $attachedItemDTO
            );

            $this->createAssessmentables(
                $assessment, $assessmentable, $itemable
            );
        }
        
        return $assessment->refresh();
    }

    private function checkExistenceOfAssessment
    (
        Assessment $assessment,
        $assessmentable,
        $itemable,
        $attachedItemDTO
    )
    {
        if ($assessment->doesntHaveSpecificAssessmentable($assessmentable, $itemable)) {
            return;
        }

        $this->throwAssessmentException(
            message: "please the {$attachedItemDTO->item} with id {$attachedItemDTO->itemId} already has the assessment with name: {$assessment->name}",
            data: $attachedItemDTO
        );
    }

    private function createAssessmentables
    (
        $assessment,
        $assessmentable,
        $itemable
    )
    {
        $assessmentables = $assessment->assessmentables()->create();
        $assessmentables->assessmentable()->associate($assessmentable);
        $assessmentables->itemable()->associate($itemable);
        $assessmentables->save();

        return $assessmentables;
    }

    private function getItemable($dto, $item)
    {
        if ($dto->item === 'courseSection') {
            return $item->course;
        }

        if ($dto->item !== 'subject') {
            return $item;
        }

        if (!$dto->extraItemId) {
            $this->throwAssessmentException(
                message: "class id is required for this {$item->name} subject",
                data: $dto
            );
        }

        return $this->getModel('class', $dto->extraItemId);
    }

    private function checkCanAttachOrDetachItem
    (
        $item,
        $assessmentDTO,
        $itemable
    )
    {
        $userIds = $item->getAuthorizedUserIds(
            onlyMain: true
        ) ?? [];
        
        $type = class_basename_lower($item);
        if ($type === 'subject' || $type === 'courseSection') {
            array_push(
                $userIds, 
                ...$itemable->getAuthorizedUserIds(
                        onlyMain: true
                    )
            );
        }

        if (in_array($assessmentDTO->userId, $userIds)) {
            return;
        }

        $this->throwAssessmentException(
            message: "you are not authorized to add/remove an assessment to/from {$type} with id {$item->id}. If is a mistake, please do report this.",
            data: $assessmentDTO
        );
    }

    public function detachAssessmentFromItems
    (
        Assessment $assessment,
        AssessmentDTO $assessmentDTO
    ) : array
    {
        $detachedItems = [];
        foreach ($assessmentDTO->unattachedItems as $unattachedItemDTO) {
            $assessmentable = $this->getModel($unattachedItemDTO->item, $unattachedItemDTO->itemId);

            $itemable = $this->getItemable($unattachedItemDTO, $assessmentable);

            if ($this->detachItem($assessmentable, $itemable, $assessment)) {
                $detachedItems[] = $assessmentable;
            }

            $assessmentable->save();
        }
        
        return [$assessment->refresh(), $detachedItems];
    }

    private function detachItem
    (
        $assessmentable, 
        $itemable, 
        $assessment
    ) : bool
    {
        return $assessment->specificAssessmentable(
                $assessmentable, $itemable
            )?->delete();
    }

    public function addAssessmentSections
    (
        Assessment $assessment,
        AssessmentDTO $assessmentDTO,
        bool $checkIfTaken = false
    ) : Assessment
    {
        if ($checkIfTaken) {
            $this->ensureAssessmentNotTaken($assessment);
        }
        
        foreach ($assessmentDTO->assessmentSections as  $assessmentSectionDTO) {

            $assessmentSectionDTO = $assessmentSectionDTO
                ->withAssessment($assessment)
                ->withAddedby($assessmentDTO->addedby);
            
            (new AssessmentSectionService)->createAssessmentSection(
                $assessmentSectionDTO
            );
        }
        
        return $assessment->refresh();
    }

    private function createOrUpdateAssessment
    (
        AssessmentDTO $assessmentDTO,
        string $method
    ) : Assessment
    {
        $data = [
            'name' => $assessmentDTO->name,
            'description' => $assessmentDTO->description,
            'duration' => $assessmentDTO->duration,
            'state' => $assessmentDTO->state ? 
                Str::upper($assessmentDTO->state): "ACCEPTED",
            'restricted' => $assessmentDTO->restricted,
            'type' => $assessmentDTO->type,
            'total_mark' => $assessmentDTO->totalMark,
            'due_at' => $assessmentDTO->dueAt?->toDateString(),
            'published_at' => $assessmentDTO->publishedAt?->toDateString(),
        ];

        if ($method === 'create') {
            $assessment = $assessmentDTO->addedby->addedAssessments()
                ->create($data);            
        }

        if ($method === 'update') {
            $assessment = getYourEduModel('assessment', $assessmentDTO->assessmentId);
                
            $assessment?->update($data);
        }

        if (is_null($assessment)) {
            $this->throwAssessmentException(
                message: "failed to {$method} assessment.",
                data: $assessmentDTO
            );
        }

        return $assessment->refresh();
    }

    private function throwAssessmentException
    (
        $message,
        $data = null
    )
    {
        throw new AssessmentException(
            message: $message, data: $data
        );
    }

    public function updateAssessment
    (
        AssessmentDTO $assessmentDTO
    ) : Assessment
    {
        ray($assessmentDTO)->green();
        try {
            DB::beginTransaction();

            $assessmentDTO->addedby = $this->getModel(
                $assessmentDTO->account,
                $assessmentDTO->accountId
            );

            $assessment = $this->getAssessmentWithId($assessmentDTO);

            $this->checkAuthorization($assessment, $assessmentDTO);
            
            $this->checkRequiredData($assessment, $assessmentDTO);

            $assessment = $this->createOrUpdateAssessment($assessmentDTO, 'update');

            $this->comparePublishedAndDueDates($assessment, $assessmentDTO);
            
            $assessment = $this->editAssessmentSections(
                $assessment, $assessmentDTO
            );

            $assessment = $this->removeAssessmentSections(
                $assessment, $assessmentDTO
            );

            $assessment = $this->addAssessmentSections(
                $assessment, $assessmentDTO, true
            );

            $this->checkAssessmentSections($assessment);
            
            $assessment = $this->attachAssessmentToItems($assessment,$assessmentDTO);

            list($assessment, $detachedItems) = $this->detachAssessmentFromItems($assessment,$assessmentDTO);

            $assessment = $this->createAutoDiscussion($assessment, $assessmentDTO);

            $this->notifyAttachedItemsAccounts(
                assessment: $assessment,
                message: "assessment with name {$assessment->name} has been updated.",
                detachedItems: $detachedItems
            );

            $assessmentDTO->methodType = 'updated';
            $this->broadcastAssessment($assessment, $assessmentDTO);

            return $assessment;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            $this->throwAssessmentException(
                message: "oops! something happened",
                data: $assessmentDTO
            );
        }
    }

    private function checkAssessmentSections(Assessment $assessment)
    {
        if ($assessment->doesntHaveAssessmentSections()) {
            $this->throwAssessmentException(
                message: "an assessment requires at least one assessment section.",
                data: $assessment
            );
        }
    }

    private function ensureAssessmentNotTaken
    (
        Assessment $assessment,
    ) : void
    {
        if ($assessment->hasBeenTaken()) {
            $this->throwAssessmentException(
                message: "this assessment has already been taken and cannot have sections or questions changed.",
                data: $assessment
            );
        }
    }

    private function editAssessmentSections
    (
        Assessment $assessment,
        AssessmentDTO $assessmentDTO
    ) : Assessment
    {
        if (count($assessmentDTO->editedAssessmentSections) > 0 ) {
            $this->ensureAssessmentNotTaken($assessment);
        }
        
        foreach ($assessmentDTO->editedAssessmentSections as $assessmentSectionDTO) {
            
            $assessmentSectionDTO = $assessmentSectionDTO
                ->withAssessment($assessment)
                ->withAddedby($assessmentDTO->addedby);

            (new AssessmentSectionService)->updateAssessmentSection(
                $assessmentSectionDTO
            );
        }

        return $assessment->refresh();
    }

    private function removeAssessmentSections
    (
        Assessment $assessment,
        AssessmentDTO $assessmentDTO
    ) : Assessment
    {
        if (count($assessmentDTO->removedAssessmentSections) > 0) {
            $this->ensureAssessmentNotTaken($assessment);
        }

        foreach ($assessmentDTO->removedAssessmentSections as $assessmentSectionDTO) {
            
            $assessmentSectionDTO = $assessmentSectionDTO
                ->withAssessment($assessment);

            (new AssessmentSectionService)->deleteAssessmentSection(
                $assessmentSectionDTO
            );
        }
        return $assessment->refresh();
    }

    private function deleteAssessmentSections(Assessment $assessment)
    {
        $assessment->assessmentSections->each(function($assessmentSection) {
            $assessmentSection->questions()->delete();
            $assessmentSection->delete();
        });
    }

    public function deleteAssessment
    (
        AssessmentDTO $assessmentDTO
    ) 
    {
        try {
            DB::beginTransaction();

            $assessmentDTO->addedby = $this->getModel(
                $assessmentDTO->account, 
                $assessmentDTO->accountId
            );
            
            $assessment = $this->getAssessmentWithId($assessmentDTO);

            $this->checkAuthorization($assessment, $assessmentDTO);

            if ($assessmentDTO->action === 'undo') {
                $assessmentDTO->state = 'accepted';
                return $this->changeState($assessment,$assessmentDTO);
            } 

            if ($this->paymentMadeFor($assessment) || $this->usedByAnotherItem($assessment)) {
                $assessmentDTO->state = 'deleted';
                return $this->changeState($assessment,$assessmentDTO);
            }

            $this->deleteAssessmentSections($assessment);

            list($assessment, $detachedItems) = $this->detachAssessmentFromAllItems(
                $assessment, $assessmentDTO
            );

            $this->deleteDiscussion($assessment, $assessmentDTO);
            $this->removeAssessment($assessment, $assessmentDTO);

            $this->notifyAttachedItemsAccounts(
                assessment: $assessment,
                message: "assessment with name {$assessment->name} has been deleted.",
                detachedItems: $detachedItems,
                onlyDetached: true
            );

            $assessmentDTO->methodType = 'deleted';
            $assessmentDTO->assessmentables = $detachedItems;
            $this->broadcastAssessment($assessment, $assessmentDTO);

            return $assessment;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            $this->throwAssessmentException(
                message: "oops! something happened",
                data: $assessmentDTO
            );
        }
        
    }private function createAutoDiscussion
    (
        $assessment,
        $assessmentDTO,
    )
    {
        if ($assessment->hasDiscussion()) {
            return $assessment;
        }

        if (!$assessmentDTO->discussionData) {
            return $assessment;
        }

        $discussion = (new DiscussionService())->createDiscussion(
            $assessmentDTO->account,
            $assessmentDTO->accountId,
            $assessmentDTO->discussionData->title,
            $assessmentDTO->discussionData->preamble,
            $assessmentDTO->discussionData->restricted ?? false,
            $assessmentDTO->discussionData->type ?? 'PRIVATE',
            $assessmentDTO->discussionData->allowed ?? 'ALL',
            $assessmentDTO->discussionFiles,
            null
        );
        
        $assessment->discussions()->save($discussion);

        return $assessment->refresh();
    }

    private function deleteDiscussion($assessment, $assessmentDTO)
    {
        if ($assessment->doesntHaveDiscussion()) {
            return $assessment;
        }

        (new DiscussionService)->deleteDiscussion(
            DiscussionDTO::createFromData(
                discussionId: $assessment->discussion()->id,
                userId: $assessmentDTO->userId
            )
        );

        return $assessment->refresh();
    }

    private function changeState
    (
        $assessment,
        $assessmentDTO
    )
    {
        $assessment->update(['state' => Str::upper($assessmentDTO->state)]);
        
        $assessmentDTO->methodType = 'updated';
        $this->broadcastAssessment($assessment, $assessmentDTO);

        return $assessment;
    }

    private function broadcastAssessment
    (
        $assessment,
        $assessmentDTO,
    )
    {
        $event = $this->getEvent($assessment, $assessmentDTO);

        if (is_null($event)) {
            return;
        }

        broadcast($event)->toOthers();
    }

    private function getEvent
    (
        $assessment,
        $assessmentDTO,
    )
    {
        if ($assessmentDTO->methodType === 'created') {
            return new NewAssessmentEvent($assessment);
        }

        if ($assessmentDTO->methodType === 'updated') {
            return new UpdateAssessmentEvent($assessment);
        }

        if ($assessmentDTO->methodType === 'deleted') {
            return new DeleteAssessmentEvent($assessmentDTO);
        }

        return null;
    }

    private function paymentMadeFor($assessment)
    {
        if ($assessment->hasPayments()) {
            return true;
        }

        return false;
    }

    private function usedByAnotherItem($assessment)
    {
        if ($assessment->isUsedByAnotherItem()) {
            return true;
        }

        return false;
    }

    private function checkAuthorization($assessment, $assessmentDTO)
    {
        if (!in_array($assessmentDTO->userId, $assessment->addedby->authorizedIds())) {
            $this->throwAssessmentException(
                message: "you are not authorized to update this assessment with id {$assessment->id}"
            );
        }
        return true;
    }

    private function getAssessmentWithId(AssessmentDTO $assessmentDTO)
    {
        return $this->getModel('assessment', $assessmentDTO->assessmentId);
    }
    
    private function removeAssessment
    (
        Assessment $assessment,
        AssessmentDTO $assessmentDTO
    ) : bool
    {
        $deletionStatus = $assessment->delete();

        if (!$deletionStatus) {
            $this->throwAssessmentException(
                message: "deletion of assessment failed.",
                data: $assessmentDTO
            );
        }

        return $deletionStatus;
    }

    public function getWork(AssessmentDTO $assessmentDTO)
    {
        ray($assessmentDTO)->green();
        $assessment = $this->getAssessmentWithId($assessmentDTO);

        if (! $this->isValidUser($assessmentDTO)) {
            return $this->getAssessmentMiniResource($assessment);
        }

        $hasAccess = $this->checkWorkAccess($assessment, $assessmentDTO);

        if (! $hasAccess) {
            return $this->getAssessmentMiniResource($assessment);
        }

        return $this->getAssessmentResource($assessment);
    }

    private function getAssessmentMiniResource($assessment)
    {
        return new AssessmentMiniResource($assessment);
    }

    private function getAssessmentResource($assessment)
    {
        return new AssessmentResource($assessment);
    }

    private function checkWorkAccess
    (
        Assessment $assessment,
        AssessmentDTO $assessmentDTO
    ) : bool
    {
        if ($assessment->isntUsedByAnotherItem()) {
            return false;
        }

        if ($this->hasAccessToItems($assessment->lessons, $assessmentDTO->userId)) {
            return true;
        }

        if ($this->hasAccessToItems($assessment->courses, $assessmentDTO->userId)) {
            return true;
        }

        if ($this->hasAccessToItems($assessment->extracurriculums, $assessmentDTO->userId)) {
            return true;
        }

        if ($this->hasAccessToItems($assessment->classes, $assessmentDTO->userId)) {
            return true;
        }

        if ($this->hasAccessToItems($assessment->programs, $assessmentDTO->userId)) {
            return true;
        }

        return false;
    }

    private function hasAccessToItems($items, $userId)
    {
        if (!count($items)) {
            return false;
        }

        foreach ($items as $item) {
            if (in_array($userId, $item->getAuthorizedLearnerUserIds())) {
                return true;
            }
        }

        return false;
    }

    public static function learnerHasAccessByUserId
    (
        Assessment $assessment, $userId
    ) : bool
    {
        foreach ($assessment->allItems() as $item) {
            if (in_array($userId, $item->getAuthorizedLearnerUserIds())) {
                return true;
            }
        }

        return false;
    }

}