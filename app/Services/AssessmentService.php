<?php

namespace App\Services;

use App\DTOs\AssessmentAnsweringDTO;
use App\DTOs\AssessmentDTO;
use App\DTOs\AssessmentMarkingDTO;
use App\DTOs\DiscussionDTO;
use App\DTOs\InvitationDTO;
use App\DTOs\MarkDTO;
use App\DTOs\SearchDTO;
use App\DTOs\WorkDTO;
use App\Events\DeleteAssessmentEvent;
use App\Events\NewAssessmentEvent;
use App\Events\NewAssessmentMarker;
use App\Events\NewAssessmentParticipant;
use App\Events\NewAssessmentPendingParticipant;
use App\Events\RemoveAssessmentMarker;
use App\Events\RemoveAssessmentParticipant;
use App\Events\RemoveAssessmentPendingParticipant;
use App\Events\UpdateAssessmentEvent;
use App\Events\UpdateAssessmentParticipant;
use App\Exceptions\AssessmentException;
use App\Http\Resources\AssessmentMiniResource;
use App\Http\Resources\AssessmentResource;
use App\Http\Resources\DiscussionParticipantResource;
use App\Http\Resources\UserAccountResource;
use App\Notifications\AssessmentAnsweredNotification;
use App\Notifications\AssessmentAnswerMarkedNotification;
use App\Notifications\AssessmentInvitationNotification;
use App\Notifications\AssessmentInvitationResponseNotification;
use App\Notifications\AssessmentJoinNotification;
use App\Notifications\AssessmentJoinRequestNotification;
use App\Notifications\AssessmentJoinResponseNotification;
use App\Notifications\AssessmentNotification;
use App\Notifications\RemoveAssessmentMarkerNotification;
use App\Notifications\RemoveAssessmentParticipantNotification;
use App\Notifications\UpdateAssessmentParticipantNotification;
use App\Traits\InvitationTrait;
use App\Traits\ParticipationServiceTrait;
use App\Traits\ServiceTrait;
use App\User;
use App\YourEdu\Assessment;
use App\YourEdu\Assessmentable;
use App\YourEdu\Profile;
use App\YourEdu\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class AssessmentService
{
    use ServiceTrait,
        InvitationTrait,
        ParticipationServiceTrait;

    const VALIDANSWERTYPES = [
        'TRUE_FALSE', 'SHORT_ANSWER', 'LONG_ANSWER', 'IMAGE', 'VIDEO',
        'AUDIO', 'OPTION', 'NUMBER', 'ARRANGE', 'FLOW', 'FILE'
    ];

    const PAGINATION = 10;

    public static function getAnswerType($answerType)
    {
        if (!strlen($answerType)) {
            return 'SHORT_ANSWER';
        }

        if (str_contains(strtolower($answerType), 'short')) {
            return 'SHORT_ANSWER';
        }

        if (str_contains(strtolower($answerType), 'long')) {
            return 'LONG_ANSWER';
        }

        if (str_contains(strtolower($answerType), 'true')) {
            return 'TRUE_FALSE';
        }

        if (!in_array(strtoupper($answerType), self::VALIDANSWERTYPES)) {
            return 'SHORT_ANSWER';
        }

        return strtoupper($answerType);
    }

    public function createAssessment(AssessmentDTO $assessmentDTO): Assessment
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

            $assessment = $this->createOrUpdateAssessment($assessmentDTO->addData(method: 'create'));

            $this->comparePublishedAndDueDates($assessment, $assessmentDTO);

            $assessment = $this->addAssessmentSections($assessment, $assessmentDTO);

            $assessmentDTO = $assessmentDTO->withAssessment($assessment);

            $assessment = $this->attachAssessmentToItems($assessmentDTO);

            $this->checkAssessmentSections($assessment);

            $assessment = $this->createAutoDiscussion($assessmentDTO);

            $assessment = $this->addAttachmentsToAssessment($assessmentDTO);

            $this->notifyAttachedItemsAccounts(
                $assessment,
                "am assessment with name: {$assessment->name} has been added.",
            );

            $assessmentDTO->methodType = 'created';
            $this->broadcastAssessment($assessmentDTO);

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

    private function addAttachmentsToAssessment($assessmentDTO)
    {
        $attachmentDTO = AttachmentDTO::new()
            ->withAttachable($assessmentDTO->assessment)
            ->withAddedby($assessmentDTO->addedby);

        foreach ($assessmentDTO->attachments as $attachment) {

            (new AttachmentService)->attach(
                $attachmentDTO
                    ->addData(note: $attachment->note ?? null)
                    ->withAttachedwith($this->getModel($attachment->type, $attachment->typeId))
            );
        }

        return $assessmentDTO->assessment->refresh();
    }

    private function comparePublishedAndDueDates(
        Assessment $assessment,
        AssessmentDTO $assessmentDTO
    ) {
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

    private function checkRequiredData(
        Assessment $assessment = null,
        AssessmentDTO $assessmentDTO
    ) {
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

    private function notifyAttachedItemsAccounts(
        Assessment $assessment,
        string $message,
        $authority = false,
        $detachedItems = [],
        $onlyDetached = false,
    ): void {
        $userIds = [];

        if (!$onlyDetached) {
            $userIds = $this->getItemsUserIds(
                assessment: $assessment,
                authority: $authority
            );
        }

        foreach ($detachedItems as $item) {
            array_push($userIds, ...$item->getAuthorizedUserIds($authority));
        }

        $this->notifyUsers(
            users: User::whereIn('id', $userIds)->get(),
            message: $message,
            assessment: $assessment
        );
    }

    private function detachAssessmentFromAllItems(
        AssessmentDTO $assessmentDTO
    ): array {
        $detachedItems = $assessmentDTO->assessment->items()->unique();

        $assessmentDTO->assessment->assessmentables->each(
            function ($assessmentable) use ($assessmentDTO) {

                $this->checkCanAttachOrDetachItem(
                    $assessmentable->assessmentable,
                    $assessmentDTO,
                    $assessmentable->itemable,
                );

                $assessmentable->delete();
            }
        );

        return [$assessmentDTO->assessment->refresh(), $detachedItems];
    }

    private function getItemsUserIds(
        Assessment $assessment,
        bool $authority = false
    ): array {
        $userIds = [];

        foreach ($assessment->allItems() as $item) {
            array_push($userIds, ...$item->getAuthorizedUserIds($authority));
        }

        return array_unique($userIds);
    }

    private function notifyUsers(
        Collection $users,
        $message,
        Assessment $assessment = null
    ) {
        Notification::send(
            $users,
            new AssessmentNotification(
                message: $message,
                assessmentResource: new AssessmentResource($assessment)
            )
        );
    }

    public function attachAssessmentToItems(
        AssessmentDTO $assessmentDTO
    ): Assessment {
        foreach ($assessmentDTO->attachedItems as $attachedItemDTO) {
            $assessmentable = $this->getModel($attachedItemDTO->item, $attachedItemDTO->itemId);

            $itemable = $this->getItemable($attachedItemDTO, $assessmentable);

            $this->checkCanAttachOrDetachItem(
                $assessmentable,
                $assessmentDTO,
                $attachedItemDTO
            );

            $this->checkExistenceOfAssessment(
                $assessmentDTO->assessment,
                $assessmentable,
                $itemable,
                $attachedItemDTO
            );

            $this->createAssessmentables(
                $assessmentDTO->assessment,
                $assessmentable,
                $itemable
            );
        }

        return $assessmentDTO->assessment->refresh();
    }

    private function checkExistenceOfAssessment(
        Assessment $assessment,
        $assessmentable,
        $itemable,
        $attachedItemDTO
    ) {
        if ($assessment->doesntHaveSpecificAssessmentable($assessmentable, $itemable)) {
            return;
        }

        $this->throwAssessmentException(
            message: "please the {$attachedItemDTO->item} with id {$attachedItemDTO->itemId} already has the assessment with name: {$assessment->name}",
            data: $attachedItemDTO
        );
    }

    private function createAssessmentables(
        $assessment,
        $assessmentable,
        $itemable = null
    ) {
        $assessmentable = $assessment->assessmentables()->create();
        $assessmentable->assessmentable()->associate($assessmentable);

        if ($itemable) {
            $assessmentable->itemable()->associate($itemable);
        }

        $assessmentable->save();

        return $assessmentable;
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

    private function checkCanAttachOrDetachItem(
        $item,
        $assessmentDTO,
        $itemable
    ) {
        $type = class_basename_lower($item);

        if (in_array($type, ['facilitator', 'professional'])) {
            return;
        }

        $userIds = $item->getAuthorizedUserIds(
            onlyMain: true
        ) ?? [];

        if ($type === 'subject' || $type === 'courseSection') {
            array_push(
                $userIds,
                ...$itemable->getAuthorizedUserIds(onlyMain: true)
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

    public function detachAssessmentFromItems(
        Assessment $assessment,
        AssessmentDTO $assessmentDTO
    ): array {
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

    private function detachItem(
        $assessmentable,
        $itemable,
        $assessment
    ): bool {
        return (bool) $assessment->specificAssessmentable(
            $assessmentable,
            $itemable
        )?->delete();
    }

    public function addAssessmentSections(
        Assessment $assessment,
        AssessmentDTO $assessmentDTO,
        bool $checkIfTaken = false
    ): Assessment {
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

    private function createOrUpdateAssessment(AssessmentDTO $assessmentDTO): Assessment
    {
        if ($assessmentDTO->method === 'create') {
            return $assessmentDTO->addedby->addedAssessments()
                ->create([
                    'name' => $assessmentDTO->name,
                    'description' => $assessmentDTO->description,
                    'duration' => $assessmentDTO->duration,
                    'social' => $assessmentDTO->social,
                    'state' => $assessmentDTO->state ?
                        Str::upper($assessmentDTO->state) : "ACCEPTED",
                    'type' => $assessmentDTO->type,
                    'total_mark' => $assessmentDTO->totalMark ?: 100,
                    'due_at' => $assessmentDTO->dueAt?->toDateString(),
                    'published_at' => $assessmentDTO->publishedAt?->toDateString(),
                ]);
        }

        $assessment = $this->getModel('assessment', $assessmentDTO->assessmentId);

        $assessment->update($this->getUpdateData($assessmentDTO));

        return $assessment->refresh();
    }

    private function getUpdateData($assessmentDTO)
    {
        $data = [];

        if ($assessmentDTO->name) {
            $data['name'] = $assessmentDTO->name;
        }

        if ($assessmentDTO->description) {
            $data['description'] = $assessmentDTO->description;
        }

        if ($assessmentDTO->duration) {
            $data['duration'] = $assessmentDTO->duration;
        }

        if ($assessmentDTO->type) {
            $data['type'] = Str::upper($assessmentDTO->type);
        }

        if ($assessmentDTO->state) {
            $data['state'] = Str::upper($assessmentDTO->state);
        }

        if ($assessmentDTO->totalMark) {
            $data['total_mark'] = $assessmentDTO->totalMark ?: 100;
        }

        if ($assessmentDTO->dueAt) {
            $data['due_at'] = $assessmentDTO->dueAt?->toDateString();
        }

        if ($assessmentDTO->publishedAt) {
            $data['published_at'] = $assessmentDTO->publishedAt?->toDateString();
        }

        return $data;
    }

    private function throwAssessmentException($message, $data = null)
    {
        throw new AssessmentException(
            message: $message,
            data: $data
        );
    }

    public function updateAssessment(AssessmentDTO $assessmentDTO): Assessment
    {
        try {
            DB::beginTransaction();

            $assessmentDTO->addedby = $this->getModel(
                $assessmentDTO->account,
                $assessmentDTO->accountId
            );

            $assessment = $this->getAssessmentWithId($assessmentDTO);

            $assessmentDTO = $assessmentDTO->withAssessment($assessment);

            $this->checkAuthorization($assessmentDTO);

            $this->checkRequiredData($assessment, $assessmentDTO);

            $assessment = $this->createOrUpdateAssessment($assessmentDTO->addData(method: 'create'));

            $this->comparePublishedAndDueDates($assessment, $assessmentDTO);

            $assessment = $this->editAssessmentSections(
                $assessment,
                $assessmentDTO
            );

            $assessment = $this->removeAssessmentSections(
                $assessment,
                $assessmentDTO
            );

            $assessment = $this->addAssessmentSections(
                $assessment,
                $assessmentDTO,
                true
            );

            $this->checkAssessmentSections($assessment);

            $assessmentDTO = $assessmentDTO->withAssessment($assessment);

            $assessment = $this->attachAssessmentToItems($assessmentDTO);

            list($assessment, $detachedItems) = $this->detachAssessmentFromItems($assessment, $assessmentDTO);

            $assessment = $this->createAutoDiscussion($assessmentDTO);

            $assessment = $this->addAttachmentsToAssessment($assessmentDTO);

            $assessment = $this->removeAttachmentsFromAssessment($assessmentDTO);

            $this->notifyAttachedItemsAccounts(
                assessment: $assessment,
                message: "assessment with name {$assessment->name} has been updated.",
                detachedItems: $detachedItems
            );

            $assessmentDTO->methodType = 'updated';
            $this->broadcastAssessment($assessmentDTO);

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

    private function removeAttachmentsFromAssessment(AssessmentDTO $assessmentDTO)
    {
        foreach ($assessmentDTO->removedAttachments as $attachment) {
            (new AttachmentService)->detach(
                AttachmentDTO::new()
                    ->withAttachable($assessmentDTO->assessment)
                    ->withAttachedwith($this->getModel($attachment->item, $attachment->itemid))
            );
        }

        return $assessmentDTO->assessment->refresh();
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

    private function ensureAssessmentNotTaken(
        Assessment $assessment,
    ): void {
        if ($assessment->hasBeenTaken()) {
            $this->throwAssessmentException(
                message: "this assessment has already been taken and cannot have sections or questions changed.",
                data: $assessment
            );
        }
    }

    private function editAssessmentSections(
        Assessment $assessment,
        AssessmentDTO $assessmentDTO
    ): Assessment {
        if (count($assessmentDTO->editedAssessmentSections) > 0) {
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

    private function removeAssessmentSections(
        Assessment $assessment,
        AssessmentDTO $assessmentDTO
    ): Assessment {
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
        $assessment->assessmentSections->each(function ($assessmentSection) {
            $assessmentSection->questions()->delete();
            $assessmentSection->delete();
        });
    }

    public function deleteAssessment(
        AssessmentDTO $assessmentDTO
    ) {
        try {
            DB::beginTransaction();

            $assessmentDTO->addedby = $this->getModel(
                $assessmentDTO->account,
                $assessmentDTO->accountId
            );

            $assessment = $this->getAssessmentWithId($assessmentDTO);

            $assessmentDTO = $assessmentDTO->withAssessment($assessment);

            $this->checkAuthorization($assessmentDTO);

            if ($assessmentDTO->action === 'undo') {
                $assessmentDTO->state = 'accepted';
                return $this->changeState($assessment, $assessmentDTO);
            }

            if ($this->paymentMadeFor($assessment) || $this->usedByAnotherItem($assessment)) {
                $assessmentDTO->state = 'deleted';
                return $this->changeState($assessment, $assessmentDTO);
            }

            $this->deleteAssessmentSections($assessment);

            $assessmentDTO = $assessmentDTO->withAssessment($assessment);

            list($assessment, $detachedItems) = $this->detachAssessmentFromAllItems($assessmentDTO);

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
            $this->broadcastAssessment($assessmentDTO->withAssessment($assessment));

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

    private function createAutoDiscussion($assessmentDTO)
    {
        if ($assessmentDTO->assessment->hasDiscussion()) {
            return $assessmentDTO->assessment;
        }

        if (!$assessmentDTO->discussionData) {
            return $assessmentDTO->assessment;
        }

        $discussion = (new DiscussionService())->createDiscussion(
            DiscussionDTO::createFromData($assessmentDTO->discussionData)
                ->withRaisedby($assessmentDTO->assessment->addedby)
                ->withFiles($assessmentDTO->discussionFiles),
        );

        $assessmentDTO->assessment->discussions()->save($discussion);

        return $assessmentDTO->assessment->refresh();
    }

    private function deleteDiscussion($assessment, $assessmentDTO)
    {
        if ($assessment->doesntHaveDiscussion()) {
            return $assessment;
        }

        (new DiscussionService)->deleteDiscussion(
            DiscussionDTO::new()->addData(
                discussionId: $assessment->discussion()->id,
                userId: $assessmentDTO->userId
            )
        );

        return $assessment->refresh();
    }

    private function changeState(
        $assessment,
        $assessmentDTO
    ) {
        $assessment->update(['state' => Str::upper($assessmentDTO->state)]);

        $assessmentDTO->methodType = 'updated';
        $this->broadcastAssessment($assessmentDTO->withAssessment($assessment));

        return $assessment;
    }

    private function broadcastAssessment(
        $assessmentDTO,
    ) {
        $event = $this->getEvent($assessmentDTO);

        if (is_null($event)) {
            return;
        }

        broadcast($event)->toOthers();
    }

    private function getEvent($dto)
    {
        if ($dto->methodType === 'created') {
            return new NewAssessmentEvent($dto->assessment);
        }

        if ($dto->methodType === 'updated') {
            return new UpdateAssessmentEvent($dto->assessment);
        }

        if ($dto->methodType === 'deleted') {
            return new DeleteAssessmentEvent($dto);
        }

        if ($dto->methodType === 'joinAssessment') {
            return new NewAssessmentParticipant($dto);
        }

        if ($dto->methodType === 'joinAsMarker') {
            return new NewAssessmentMarker($dto);
        }

        if ($dto->methodType === 'removeMarker') {
            return new RemoveAssessmentMarker($dto);
        }

        if ($dto->methodType === 'joinRequest') {
            return new NewAssessmentPendingParticipant($dto);
        }

        if ($dto->methodType === 'updateParticipant') {
            return new UpdateAssessmentParticipant($dto);
        }

        if ($dto->methodType === 'deleteParticipant') {
            return new RemoveAssessmentParticipant($dto);
        }

        if ($dto->methodType === 'pendingParticipant') {
            return new NewAssessmentPendingParticipant($dto);
        }

        if ($dto->methodType === 'removePendingParticipant') {
            return new RemoveAssessmentPendingParticipant($dto);
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

    private function checkAuthorization($assessmentDTO)
    {
        if (in_array($assessmentDTO->userId, $assessmentDTO->assessment->addedby->getAuthorizedIds())) {
            return true;
        }

        $this->throwAssessmentException(
            message: "you are not authorized to update this assessment with id {$assessmentDTO->assessment->id}",
            data: $assessmentDTO
        );
    }

    private function getAssessmentWithId($dto)
    {
        return $this->getModel('assessment', $dto->assessmentId);
    }

    private function removeAssessment(
        Assessment $assessment,
        AssessmentDTO $assessmentDTO
    ): bool {
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
        $assessment = $this->getAssessmentWithId($assessmentDTO);

        if (!$this->isValidUser($assessmentDTO)) {
            return $this->getAssessmentMiniResource($assessment);
        }

        if ($assessment->doesnthaveAccess()) {
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

    public static function learnerHasAccessByUserId(
        Assessment $assessment,
        $userId
    ): bool {
        foreach ($assessment->allItems() as $item) {
            if (in_array($userId, $item->getAuthorizedLearnerUserIds())) {
                return true;
            }
        }

        return false;
    }

    public function getParticipants(AssessmentDTO $assessmentDTO)
    {
        $this->getModel('assessment', $assessmentDTO->assessmentId);

        if (is_null($assessmentDTO->state)) {
            $assessmentDTO->state = 'active';
        }

        $participantsProfiles = Profile::query()
            ->wherePartOf(
                'assessment',
                $assessmentDTO->assessmentId,
                $assessmentDTO->state
            )
            ->orderBy('name')
            ->paginate(self::PAGINATION);

        return $participantsProfiles;
    }

    private function getParticipant($assessmentDTO)
    {
        if (is_null($assessmentDTO->assessment)) {
            return $this->getModel('participant', $assessmentDTO->participantId);
        }

        $participant = $assessmentDTO->assessment->getParticipant($assessmentDTO->participantId);

        if (is_not_null($participant)) {
            return $participant;
        }

        $this->throwAssessmentException(
            message: "sorry 😞, participant with id {$assessmentDTO->participantId} not a participant of the assessment with name: {$assessmentDTO->assessment->name}",
            data: $assessmentDTO
        );
    }

    private function ensureAccountUserDoesHaveMarkingAccount($item, $account)
    {
        if (is_null($otherAccount = $item->getMarkerAccountUsingUserId($account->user_id))) {
            return;
        }

        $this->throwAccountNotFoundException(
            message: "sorry 😞, you are already marking with your {$otherAccount->accountType} account.",
            data: [$item, $account]
        );
    }

    public function inviteParticipant(InvitationDTO $invitationDTO)
    {
        $invitationDTO = $this->setItemModel($invitationDTO);

        $invitationDTO = $this->setInvitee($invitationDTO);

        $assessmentDTO = AssessmentDTO::new()
            ->withAssessment($invitationDTO->itemModel)
            ->addData(
                userId: $invitationDTO->userId,
                action: 'pending'
            );

        $this->ensureIsOwnerOfAssessment($assessmentDTO);

        if ($invitationDTO->type === 'marker') {

            $this->validateMarkerAccount(
                $assessmentDTO->addData(account: $invitationDTO->invitee->accountType)
            );

            $this->ensureAccountUserDoesHaveMarkingAccount(
                item: $assessmentDTO->assessment,
                account: $invitationDTO->invitee
            );
        }

        $request = $this->createInviteRequest($invitationDTO);

        $invitationDTO = $invitationDTO->withRequest($request);

        if ($invitationDTO->type !== 'marker') {

            $this->ensureAccountUserDoesHaveParticipatingAccount(
                item: $assessmentDTO->assessment,
                account: $invitationDTO->invitee
            );

            $participant = $this->createParticipant(
                $invitationDTO->itemModel,
                $invitationDTO->invitee
            );

            $assessmentDTO = $assessmentDTO->withParticipant($participant);

            $participant = $this->updateParticipantStateUsingAction($assessmentDTO);

            $assessmentDTO->methodType = 'pendingParticipant';

            $this->broadcastAssessment($assessmentDTO);

            $invitationDTO->methodType = 'inviteParticipant';

            $this->sendNotification(
                $invitationDTO->withUsers($invitationDTO->invitee->user)
            );

            return $participant;
        }

        $invitationDTO->methodType = 'inviteParticipant';

        $this->sendNotification(
            $invitationDTO->withUsers($invitationDTO->invitee->user)
        );

        return null;
    }

    public function invitationResponse(InvitationDTO $invitationDTO)
    {
        $request = $this->getModel('request', $invitationDTO->requestId);

        $invitationDTO = $invitationDTO->withRequest($request);

        $this->checkRequestOwnership($invitationDTO);

        $this->checkRequestType($request);

        $this->validateAction($invitationDTO);

        $request->state = Str::upper($invitationDTO->action);
        $request->save();

        $invitationDTO = $invitationDTO->withRequest($request);

        $assessmentDTO = AssessmentDTO::new()
            ->withAssessment($request->requestable);

        if ($request->isMarkerRequest()) {
            $assessmentDTO = $assessmentDTO->withInvitationDTO($invitationDTO);

            return $this->handleMarkerInviteRequest($assessmentDTO);
        }

        $participant = $assessmentDTO->assessment
            ->getParticipantUsingAccount($invitationDTO->request->requestto);

        if (is_null($participant)) {

            $request->delete();

            ray($invitationDTO->request->refresh())->green();
            return $this->throwAssessmentException(
                message: "sorry 😞, there is no pending participant for this request.",
                data: $assessmentDTO
            );
        }

        $this->broadcastAssessment(
            $invitationDTO->addData(
                participantId: $participant->id,
                methodType: 'removePendingParticipant'
            )
        );

        $invitationDTO->methodType = 'invitationResponse';
        $this->sendNotification(
            $invitationDTO->withUsers($request->requestfrom->user),
        );

        if ($invitationDTO->action === 'declined') {

            $participant->delete();

            return null;
        }

        $participant = $this->updateParticipantStateUsingAction(
            $assessmentDTO
                ->withParticipant($participant)
                ->addData(action: 'active')
        );

        $this->broadcastAssessment(
            $assessmentDTO
                ->withParticipant($participant)
                ->addData(methodType: 'joinAssessment')
        );

        return $participant;
    }

    public function updateAssessmentParticipant(AssessmentDTO $assessmentDTO)
    {
        $participant = $this->getParticipant($assessmentDTO);

        $assessmentDTO = $assessmentDTO->withParticipant($participant);

        $assessmentDTO = $assessmentDTO->withAssessment($participant->participation);

        $this->ensureIsOwnerOfAssessment($assessmentDTO);

        $participant = $this->updateParticipantStateUsingAction($assessmentDTO);

        $assessmentDTO->methodType = 'updateParticipant';

        $this->broadcastAssessment($assessmentDTO);

        $this->sendNotification(
            $assessmentDTO->withUsers($participant->user)
        );

        return $participant;
    }

    public function joinAssessment(InvitationDTO $invitationDTO)
    {
        if ($invitationDTO->type === 'marker') {
            return $this->joinMarkers($invitationDTO);
        }

        $invitationDTO = $this->setItemModel($invitationDTO);

        $invitationDTO = $this->setJoiner($invitationDTO);

        $assessmentDTO = AssessmentDTO::new()
            ->addData(userId: $invitationDTO->userId, methodType: "joinAssessment")
            ->withInvitationDTO($invitationDTO)
            ->withAssessment($invitationDTO->itemModel);

        $this->ensureAccountUserDoesHaveParticipatingAccount(
            item: $assessmentDTO->assessment,
            account: $invitationDTO->joiner
        );

        $this->ensureNotOwnerOrParticipant($assessmentDTO);

        if ($invitationDTO->itemModel->type === 'PRIVATE') {

            return $this->sendJoinRequest($invitationDTO);
        }

        $participant = $this->createParticipant(
            $invitationDTO->itemModel,
            $invitationDTO->joiner
        );

        $this->sendNotification(
            $assessmentDTO
                ->withUsers($invitationDTO->itemModel->addedby->user)
        );

        $this->broadcastAssessment(
            $assessmentDTO->withParticipant($participant)
        );

        return $participant;
    }

    public function joinResponse(InvitationDTO $invitationDTO)
    {
        $request = $this->getModel('request', $invitationDTO->requestId);

        $this->checkRequestType($request);

        $this->validateAction($invitationDTO);

        $assessmentDTO = AssessmentDTO::new()
            ->withAssessment($request->requestable)
            ->addData(
                userId: $invitationDTO->userId,
                action: 'active'
            );

        $this->ensureIsOwnerOfAssessment($assessmentDTO);

        $request->state = Str::upper($invitationDTO->action);
        $request->save();

        if ($request->isMarkerRequest()) {

            $assessmentDTO = $assessmentDTO->withInvitationDTO(
                $invitationDTO->withRequest($request)
            );

            return $this->handleMarkerJoinRequest($assessmentDTO);
        }

        $participant = $this->getJoinRequestParticipant($assessmentDTO, $request);

        $invitationDTO->methodType = 'removePendingParticipant';
        $invitationDTO->participantId = $participant->id;
        $this->broadcastAssessment($invitationDTO);

        $invitationDTO->methodType = 'joinResponse';
        $this->sendNotification(
            $invitationDTO->withUsers($request->requestfrom->user),
        );

        if ($invitationDTO->action === 'declined') {

            $participant->delete();

            return null;
        }

        $participant = $this->updateParticipantStateUsingAction(
            $assessmentDTO->withParticipant($participant)
        );

        $assessmentDTO->methodType = 'joinAssessment';

        $this->broadcastAssessment($assessmentDTO);

        return $participant;
    }

    private function ensureAccountIsMarker($assessmentDTO)
    {
        if ($assessmentDTO->assessment->isMarker($assessmentDTO->assessmentable?->user_id)) {
            return;
        }

        $this->throwAssessmentException(
            message: "sorry 😞, {$assessmentDTO->assessmentable?->accountType} account is not a marker for this assessment.",
            data: $assessmentDTO
        );
    }

    private function handleMarkerInviteRequest($assessmentDTO)
    {
        if ($assessmentDTO->invitationDTO->action === 'declined') {
            return null;
        }

        $assessmentable = $this->createAssessmentables(
            $assessmentDTO->invitationDTO->request->requestable,
            $assessmentDTO->invitationDTO->request->requestto
        );

        $assessmentDTO->methodType = 'joinAsMarker';

        $this->broadcastAssessment(
            $assessmentDTO->withAssessmentable($assessmentDTO->invitationDTO->request->requestto)
        );

        return $assessmentable->assessmentable;
    }

    private function handleMarkerJoinRequest($assessmentDTO)
    {
        if ($assessmentDTO->invitationDTO->request->state == 'DECLINED') {
            $assessmentDTO->invitationDTO->methodType = 'joinResponse';

            $this->sendNotification(
                $assessmentDTO->invitationDTO
                    ->withUsers(
                        $assessmentDTO->invitationDTO->request->requestfrom->user
                    )
            );

            return;
        }

        $assessmentDTO->methodType = 'joinAsMarker';

        $assessmentDTO->invitationDTO = $assessmentDTO->invitationDTO
            ->withJoiner(
                $assessmentDTO->invitationDTO->request->requestfrom
            )->addData(type: 'marker');

        $assessmentDTO = $assessmentDTO
            ->withAssessmentable(
                $assessmentDTO->invitationDTO->request->requestfrom
            )
            ->withUsers(
                $assessmentDTO->invitationDTO->request->requestfrom->user
            );

        return $this->addAccountAsMarker(
            $assessmentDTO->invitationDTO->request->requestfrom,
            $assessmentDTO
        );
    }

    public static function getParticipantOrNull($account)
    {
        if (is_null($account)) {
            return null;
        }

        if (class_basename_lower($account) === 'participant') {
            return new DiscussionParticipantResource($account);
        }

        return null;
    }

    public static function getMarkerOrNull($account)
    {
        if (is_null($account)) {
            return null;
        }

        if (class_basename_lower($account) !== 'participant') {
            return new UserAccountResource($account);
        }

        return null;
    }

    public function deleteAssessmentParticipant(AssessmentDTO $assessmentDTO)
    {
        $participant = $this->getParticipant($assessmentDTO);

        $assessmentDTO = $assessmentDTO->withParticipant($participant);

        $assessmentDTO = $assessmentDTO->withAssessment($participant->participation);

        $mustBeOwner = $participant->accountable->isNotAuthorizedUser($assessmentDTO->userId);
        if ($mustBeOwner) {

            $this->ensureIsOwnerOfAssessment($assessmentDTO);
        }

        $participant->delete();

        $assessmentDTO->methodType = 'deleteParticipant';

        $this->broadcastAssessment($assessmentDTO);

        $this->sendNotification(
            $assessmentDTO->withUsers(
                $mustBeOwner ? $assessmentDTO->participant->accountable->user :
                    $assessmentDTO->assessment->addedby->user
            )
        );
    }

    public function deleteAssessmentMarker(AssessmentDTO $assessmentDTO)
    {
        $assessmentable = Assessmentable::find($assessmentDTO->participantId);

        $assessmentDTO =  $assessmentDTO->withAssessmentable(
            $assessmentable->assessmentable
        );

        $assessmentDTO = $assessmentDTO->withAssessment(
            $assessmentable->assessment
        );

        $this->ensureAccountIsMarker($assessmentDTO);

        $mustBeOwner = $assessmentDTO->assessmentable->isNotAuthorizedUser($assessmentDTO->userId);

        if ($mustBeOwner) {

            $this->ensureIsOwnerOfAssessment($assessmentDTO);
        }

        $assessmentDTO->assessmentable
            ->getMarkerUsingAssessmentId(
                $assessmentDTO->assessment->id
            )?->delete();

        $assessmentDTO->methodType = 'removeMarker';

        $this->broadcastAssessment($assessmentDTO);

        $this->sendNotification(
            $assessmentDTO->withUsers(
                $mustBeOwner ? $assessmentDTO->assessmentable->user :
                    $assessmentDTO->assessment->addedby->user
            )
        );
    }

    public function sendJoinRequest($invitationDTO)
    {
        $this->ensureNoPendingJoinRequests($invitationDTO, $invitationDTO->type === 'marker');

        $request = $this->createJoinRequest($invitationDTO);

        $invitationDTO = $invitationDTO->withRequest($request);

        $invitationDTO->methodType = 'joinRequest';

        $this->sendNotification(
            $invitationDTO
                ->withUsers($request->requestable->addedby->user),
        );

        if ($invitationDTO->type === 'marker') {
            return null;
        }

        $participant = $this->createParticipant(
            $request->requestable,
            $request->requestfrom
        );

        $assessmentDTO = AssessmentDTO::new()
            ->withParticipant($participant)
            ->addData(
                userId: $invitationDTO->userId,
                methodType: 'joinRequest',
                action: 'pending'
            );

        $this->updateParticipantStateUsingAction($assessmentDTO);

        $this->broadcastAssessment($assessmentDTO);

        return null;
    }

    public function joinMarkers(InvitationDTO $invitationDTO)
    {
        $invitationDTO = $this->setJoiner($invitationDTO);

        $invitationDTO = $this->setItemModel($invitationDTO);

        $isNotOwner = $invitationDTO->itemModel->isNotOwner($invitationDTO->userId);

        if ($isNotOwner) {

            $this->validateMarkerAccount($invitationDTO);
        }

        $assessmentDTO = AssessmentDTO::new()
            ->withAssessment($invitationDTO->itemModel)
            ->addData(
                userId: $invitationDTO->userId,
                methodType: "joinAsMarker"
            );

        $this->ensureUserIsNotMarker($assessmentDTO);

        if (
            $assessmentDTO->assessment->type === 'PRIVATE' &&
            $isNotOwner
        ) {
            return $this->sendJoinRequest($invitationDTO);
        }

        $assessmentDTO = $assessmentDTO->withInvitationDTO($invitationDTO);

        $assessmentDTO = $assessmentDTO->withUsers($assessmentDTO->assessment->addedby->user);

        return $this->addAccountAsMarker(
            $invitationDTO->joiner,
            $assessmentDTO,
        );
    }

    private function addAccountAsMarker($account, $assessmentDTO)
    {
        $this->createAssessmentables(
            $assessmentDTO->assessment,
            $account,
        );

        $assessmentDTO = $assessmentDTO->withAssessmentable($account);

        $this->broadcastAssessment($assessmentDTO);

        $this->sendNotification($assessmentDTO);

        return $account;
    }

    private function validateMarkerAccount($dto)
    {
        if (in_array($dto->account, Assessment::MARKERS_ACCOUNT_TYPES)) {
            return;
        }

        $types = implode(' or ', Assessment::MARKERS_ACCOUNT_TYPES);
        $this->throwAssessmentException(
            message: "sorry 😞, {$dto->account} account is not valid for a marker. please use {$types} account.",
            data: $dto
        );
    }

    private function ensureUserIsNotMarker($assessmentDTO)
    {
        if ($assessmentDTO->assessment->isNotMarker($assessmentDTO->userId)) {
            return;
        }

        $this->throwAssessmentException(
            message: "sorry 😞, you are already a marker for assessment with name: {$assessmentDTO->assessment->name}",
            data: $assessmentDTO
        );
    }

    private function ensureNoPendingJoinRequests($invitationDTO, $marker = false)
    {
        $request = Request::query()
            ->whereAssessmentRequest()
            ->wherePending()
            ->when($marker, function ($query) {
                $query->whereMarkerRequest();
            })
            ->whereRequestFromUser($invitationDTO->userId)
            ->exists();

        if (!$request) {
            return;
        }

        $this->throwAssessmentException(
            message: "sorry 😞, you already have a pending request to join this assessment",
            data: $invitationDTO
        );
    }

    private function ensureIsOwnerOfAssessment($assessmentDTO)
    {
        if ($assessmentDTO->assessment->isOwner($assessmentDTO->userId)) {
            return;
        }

        $this->throwAssessmentException(
            message: 'you are not authorized to perform this action on this assessment',
            data: $assessmentDTO
        );
    }

    private function ensureNotOwnerOrParticipant($assessmentDTO)
    {
        if (
            $assessmentDTO->assessment->isNotOwner($assessmentDTO->userId) &&
            $assessmentDTO->assessment->isNotParticipant($assessmentDTO->userId)
        ) {
            return;
        }

        $this->throwAssessmentException(
            message: "sorry 😞, an owner or participant cannot perform this action.",
            data: $assessmentDTO
        );
    }

    private function checkRequestType($request)
    {
        if ($request->isAssessmentRequest()) {
            return;
        }

        $this->throwAssessmentException(
            message: "request with id {$request->id} is not related to a assessment",
            data: $request
        );
    }

    private function setAnsweredby($assessmentAnsweringDTO)
    {
        if ($assessmentAnsweringDTO->answeredby) {
            return $assessmentAnsweringDTO;
        }

        return $assessmentAnsweringDTO->withAnsweredby(
            $this->getModel($assessmentAnsweringDTO->account, $assessmentAnsweringDTO->accountId)
        );
    }

    public function answerAssessment(AssessmentAnsweringDTO $assessmentAnsweringDTO)
    {
        $assessmentAnsweringDTO = $this->setAnsweredby($assessmentAnsweringDTO);

        $assessmentAnsweringDTO = $assessmentAnsweringDTO->withAssessment(
            $this->getModel('assessment', $assessmentAnsweringDTO->assessmentId)
        );

        $this->checkAccountOwnership($assessmentAnsweringDTO->answeredby, $assessmentAnsweringDTO->userId);

        $this->ensureCanAnswer($assessmentAnsweringDTO);

        $this->ensureWorkNotDone($assessmentAnsweringDTO);

        $assessmentAnsweringDTO = $assessmentAnsweringDTO->setUpAnsweredby();

        if ($assessmentAnsweringDTO->type === 'one') {
            $this->answerQuestion($assessmentAnsweringDTO);
        }

        if ($assessmentAnsweringDTO->type === 'all') {
            $this->answerAllQuestions($assessmentAnsweringDTO);

            $assessmentAnsweringDTO = $assessmentAnsweringDTO->addData(status: 'done');
        }

        $this->submitWork($assessmentAnsweringDTO);

        if ($assessmentAnsweringDTO->type !== 'all') {
            return;
        }

        $this->notifyMarkersAndOwner($assessmentAnsweringDTO);
    }

    private function ensureWorkNotDone($assessmentAnsweringDTO)
    {
        if ($assessmentAnsweringDTO->answeredby->doesntHaveWorkForAssessment($assessmentAnsweringDTO->assessment->id)) {
            return;
        }


        if ($assessmentAnsweringDTO->answeredby->doesntHaveASubmittedWorkForAssessment($assessmentAnsweringDTO->assessment->id)) {
            return;
        }

        $this->throwAssessmentException(
            message: "sorry 😞, you are done submitting this work and cannot do anything else.",
            data: $assessmentAnsweringDTO
        );
    }

    public function doneAnsweringAssessment(AssessmentAnsweringDTO $assessmentAnsweringDTO)
    {
        $assessmentAnsweringDTO = $this->setAnsweredby($assessmentAnsweringDTO);

        $assessmentAnsweringDTO = $assessmentAnsweringDTO->withAssessment(
            $this->getModel('assessment', $assessmentAnsweringDTO->assessmentId)
        );

        $this->checkAccountOwnership($assessmentAnsweringDTO->answeredby, $assessmentAnsweringDTO->userId);

        $this->ensureCanAnswer($assessmentAnsweringDTO);

        $assessmentAnsweringDTO = $assessmentAnsweringDTO->withWork(
            $this->submitWork($assessmentAnsweringDTO->addData(status: 'done'))
        );

        $this->updateSubmittedWork($assessmentAnsweringDTO);

        $this->notifyMarkersAndOwner($assessmentAnsweringDTO);
    }

    private function answerQuestion($assessmentAnsweringDTO)
    {
        $this->createAnswer(
            $assessmentAnsweringDTO->answerDTO
        );
    }

    private function answerAllQuestions($assessmentAnsweringDTO)
    {
        foreach ($assessmentAnsweringDTO->answerDTOs as $answerDTO) {

            $this->createAnswer($answerDTO);
        }
    }

    private function createAnswer($answerDTO)
    {
        return (new AnswerService)->createAnswer($answerDTO);
    }

    private function submitWork($assessmentAnsweringDTO)
    {
        $work = $assessmentAnsweringDTO->answeredby->getWorkForAssessment($assessmentAnsweringDTO->assessment->id);

        if ($work) {
            return $work;
        }

        return (new WorkService)->submitWork(
            WorkDTO::new()
                ->withAssessment($assessmentAnsweringDTO->assessment)
                ->withAddedby($assessmentAnsweringDTO->answeredby)
                ->addData(
                    accessChecked: true,
                    dontBroadcast: true,
                    status: $assessmentAnsweringDTO->status
                )
        );
    }

    private function updateSubmittedWork($assessmentAnsweringDTO)
    {
        return (new WorkService)->updateWorkStatus(
            WorkDTO::new()
                ->withWork($assessmentAnsweringDTO->work)
                ->withAddedby($assessmentAnsweringDTO->answeredby)
                ->addData(
                    accessChecked: true,
                    dontBroadcast: true,
                    status: $assessmentAnsweringDTO->status
                )
        );
    }

    private function ensureCanAnswer($assessmentAnsweringDTO)
    {
        if ($assessmentAnsweringDTO->assessment->isOwner($assessmentAnsweringDTO->userId)) {
            return;
        }

        if ($assessmentAnsweringDTO->assessment->isSocial() && $assessmentAnsweringDTO->assessment->isParticipant($assessmentAnsweringDTO->userId)) {
            return;
        }

        if ($assessmentAnsweringDTO->assessment->isNotSocial() && $assessmentAnsweringDTO->assessment->hasAccess($assessmentAnsweringDTO->userId)) {
            return;
        }

        $this->throwAssessmentException(
            message: "sorry 😞, you do not have access to the assessment with name: {$assessmentAnsweringDTO->assessment->name}",
            data: $assessmentAnsweringDTO
        );
    }

    private function notifyMarkersAndOwner($assessmentAnsweringDTO)
    {
        $assessmentAnsweringDTO->methodType = 'answered';

        $this->sendNotification(
            $assessmentAnsweringDTO->withUsers(
                $assessmentAnsweringDTO->assessment->getOwnerAndMarkersUsers()
            )
        );
    }

    private function setMarker($assessmentMarkingDTO)
    {
        if ($assessmentMarkingDTO->marker) {
            return $assessmentMarkingDTO;
        }

        return $assessmentMarkingDTO->withMarker(
            $this->getModel($assessmentMarkingDTO->account, $assessmentMarkingDTO->accountId)
        );
    }

    public function markAssessment(AssessmentMarkingDTO $assessmentMarkingDTO)
    {
        $assessmentMarkingDTO = $this->setMarker($assessmentMarkingDTO);

        $this->checkAccountOwnership($assessmentMarkingDTO->marker, $assessmentMarkingDTO->userId);

        $assessmentMarkingDTO = $assessmentMarkingDTO->withAssessment(
            $this->getAssessmentWithId($assessmentMarkingDTO)
        );

        $this->ensureCanMark($assessmentMarkingDTO);

        $assessmentMarkingDTO = $assessmentMarkingDTO->withWork(
            $this->getModel('work', $assessmentMarkingDTO->workId)
        );

        $this->ensureIsNotDoneMarkingWork($assessmentMarkingDTO);

        $this->ensureHasMarkingData($assessmentMarkingDTO);

        $assessmentMarkingDTO = $assessmentMarkingDTO->setUpMarker();

        if ($assessmentMarkingDTO->type === 'one') {

            $this->markAnswer($assessmentMarkingDTO);

            return null;
        }

        $this->markAllAnswer($assessmentMarkingDTO);

        $this->markWork($assessmentMarkingDTO);

        $this->sendNotification(
            $assessmentMarkingDTO
                ->withUsers($assessmentMarkingDTO->work->addedby->user)
                ->addData(methodType: 'marked')
        );
    }

    public function doneMarkingAssessment(AssessmentMarkingDTO $assessmentMarkingDTO)
    {
        $assessmentMarkingDTO = $this->setMarker($assessmentMarkingDTO);

        $this->checkAccountOwnership($assessmentMarkingDTO->marker, $assessmentMarkingDTO->userId);

        $assessmentMarkingDTO = $assessmentMarkingDTO->withAssessment(
            $this->getAssessmentWithId($assessmentMarkingDTO)
        );

        $this->ensureCanMark($assessmentMarkingDTO);

        $assessmentMarkingDTO = $assessmentMarkingDTO->withWork(
            $this->getModel('work', $assessmentMarkingDTO->workId)
        );

        $this->ensureIsDoneMarkingWork($assessmentMarkingDTO);

        $this->sendNotification(
            $assessmentMarkingDTO
                ->withUsers($assessmentMarkingDTO->work->addedby->user)
                ->addData(methodType: 'marked')
        );
    }

    private function ensureIsNotDoneMarkingWork($assessmentMarkingDTO)
    {
        if ($assessmentMarkingDTO->work->addedby
            ->hasAnUnmarkedAnswerForAssessmentAndByMarker(
                $assessmentMarkingDTO->assessmentId,
                $assessmentMarkingDTO->marker
            )
        ) {
            return;
        }

        $this->throwAssessmentException(
            message: "sorry 😞, you are already done marking this work",
            data: $assessmentMarkingDTO
        );
    }

    private function ensureIsDoneMarkingWork($assessmentMarkingDTO)
    {
        if ($assessmentMarkingDTO->work->addedby
            ->doesntHaveAnUnmarkedAnswerForAssessmentAndByMarker(
                $assessmentMarkingDTO->assessmentId,
                $assessmentMarkingDTO->marker
            )
        ) {
            return;
        }

        $this->throwAssessmentException(
            message: "sorry 😞, you still have not finished marking this work",
            data: $assessmentMarkingDTO
        );
    }

    private function ensureHasMarkingData($assessmentMarkingDTO)
    {
        if ($assessmentMarkingDTO->hasMarkingData()) {
            return;
        }

        $this->throwAssessmentException(
            message: "sorry 😞, you do not have data for marking one or all of the answers",
            data: $assessmentMarkingDTO
        );
    }

    private function markWork($assessmentMarkingDTO)
    {
        return $this->createMark(
            MarkDTO::new()
                ->withMarkedby($assessmentMarkingDTO->marker)
                ->withMarkable($assessmentMarkingDTO->work)
                ->addData(
                    remarks: $assessmentMarkingDTO->remarks,
                    score: $assessmentMarkingDTO->work->addedby->getTotalScoreOfAnswers($assessmentMarkingDTO->assessment),
                    scoreOver: $assessmentMarkingDTO->assessment->getTotalScoreOver(),
                )
        );
    }

    private function markAnswer($assessmentMarkingDTO)
    {
        return $this->createMark($assessmentMarkingDTO->markDTO);
    }

    private function markAllAnswer($assessmentMarkingDTO)
    {
        $marks = [];

        foreach ($assessmentMarkingDTO->markDTOs as $markDTO) {
            $marks[] = $this->createMark($markDTO);
        }

        return $marks;
    }

    private function createMark(MarkDTO $markDTO)
    {
        return (new MarkService)->createMark($markDTO);
    }

    private function ensureCanMark($assessmentMarkingDTO)
    {
        if ($assessmentMarkingDTO->assessment->isOwner($assessmentMarkingDTO->userId)) {
            return;
        }

        if (
            $assessmentMarkingDTO->assessment->isSocial() &&
            $assessmentMarkingDTO->assessment->isMarker($assessmentMarkingDTO->userId)
        ) {
            return;
        }

        if (
            $assessmentMarkingDTO->assessment->isNotSocial() &&
            $assessmentMarkingDTO->assessment->hasAccess($assessmentMarkingDTO->userId, true)
        ) {
            return;
        }

        $this->throwAssessmentException(
            message: "sorry 😞, you do not have access to mark any work submitted for the assessment with name: {$assessmentMarkingDTO->assessment->name}",
            data: $assessmentMarkingDTO
        );
    }

    private function sendNotification($dto)
    {
        if (is_null($dto->users)) {
            return;
        }

        $notification = $this->getNotification($dto);

        if (is_null($notification)) {
            return;
        }

        Notification::send($dto->users, $notification);
    }

    private function getNotification($dto)
    {
        if ($dto->methodType === 'invitationResponse') {
            return new AssessmentInvitationResponseNotification($dto);
        }

        if ($dto->methodType === 'updateParticipant') {
            return new UpdateAssessmentParticipantNotification($dto);
        }

        if ($dto->methodType === 'deleteParticipant') {
            return new RemoveAssessmentParticipantNotification($dto);
        }

        if ($dto->methodType === 'removeMarker') {
            return new RemoveAssessmentMarkerNotification($dto);
        }

        if ($dto->methodType === 'inviteParticipant' || $dto->methodType === 'pendingParticipant') {
            return new AssessmentInvitationNotification($dto);
        }

        if ($dto->methodType === 'joinResponse') {
            return new AssessmentJoinResponseNotification($dto);
        }

        if ($dto->methodType === 'joinRequest') {
            return new AssessmentJoinRequestNotification($dto);
        }

        if ($dto->methodType === 'answered') {
            return new AssessmentAnsweredNotification($dto);
        }

        if ($dto->methodType === 'marked') {
            return new AssessmentAnswerMarkedNotification($dto);
        }

        if ($dto->methodType === 'joinAssessment' || $dto->methodType === 'joinAsMarker') {
            return new AssessmentJoinNotification($dto);
        }

        return null;
    }

    public function assessmentSearch(SearchDTO $searchDTO)
    {
        return $this->profileSearch($searchDTO);
    }
}
