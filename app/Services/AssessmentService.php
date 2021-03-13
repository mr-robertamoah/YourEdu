<?php

namespace App\Services;

use App\DTOs\AssessmentDTO;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\AssessmentException;
use App\Http\Resources\AssessmentResource;
use App\Notifications\AssessmentNotification;
use App\User;
use App\YourEdu\Assessment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AssessmentService
{
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

            $assessment = $this->addAssessmentSections($assessment,$assessmentDTO);

            $assessment = $this->attachAssessmentToItems($assessment,$assessmentDTO);

            $this->checkAssessmentSections($assessment);

            $this->notifyAttachedItemsAccounts(
                $assessment,
                "am assessment with name: {$assessment->name} has been added.",
            );

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
        Assessment $assessment
    ) : array
    {
        $detachedItems = [];
        foreach ($assessment->allItems() as $item) {
            
            if ($this->detachItem($item, $assessment)) {
                $detachedItems[] = $item;
            }
            $item->save();
        }
        
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
        foreach ($assessmentDTO->attachedItems as $attachedItem) {
            $item = $this->getModel($attachedItem->item, $attachedItem->itemId);

            $item->assessments()->attach($assessment);
            $item->save();
        }
        
        return $assessment->refresh();
    }

    public function detachAssessmentFromItems
    (
        Assessment $assessment,
        AssessmentDTO $assessmentDTO
    ) : array
    {
        $detachedItems = [];
        foreach ($assessmentDTO->unattachedItems as $unattachedItem) {
            $item = $this->getModel($unattachedItem->item, $unattachedItem->itemId);

            if ($this->detachItem($item, $assessment)) {
                $detachedItems[] = $item;
            }
            $item->save();
        }
        
        return [$assessment->refresh(), $detachedItems];
    }

    private function detachItem($item, $assessment) : bool
    {
        return $item->assessments()
            ->where('id',$assessment->id)?->detach($assessment);
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
            'restricted' => $assessmentDTO->restricted,
            'type' => $assessmentDTO->type,
            'total_mark' => $assessmentDTO->totalMark,
            'due_at' => $assessmentDTO->dueAt->toDateString(),
            'published_at' => $assessmentDTO->publishedAt->toDateString(),
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

    private function getModel($account, $accountId)
    {
        $mainAccount = getYourEduModel($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} with id {$accountId} was not found.");
        }

        return $mainAccount;
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

            $this->notifyAttachedItemsAccounts(
                assessment: $assessment,
                message: "assessment with name {$assessment->name} has been updated.",
                detachedItems: $detachedItems
            );

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
           
            $assessmentDTO->addedby = $this->getModel(
                $assessmentDTO->account, 
                $assessmentDTO->accountId
            );
            
            $assessment = $this->getAssessmentWithId($assessmentDTO);

            $this->checkAuthorization($assessment, $assessmentDTO);

            $this->deleteAssessmentSections($assessment);

            list($assessment, $detachedItems) = $this->detachAssessmentFromAllItems(
                $assessment
            );

            $this->removeAssessment($assessment, $assessmentDTO);

            $this->notifyAttachedItemsAccounts(
                assessment: $assessment,
                message: "assessment with name {$assessment->name} has been deleted.",
                detachedItems: $detachedItems,
                onlyDetached: true
            );

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
                data: $assessmentData
            );
        }

        return $deletionStatus;
    }

}