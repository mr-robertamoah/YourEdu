<?php

namespace App\Services;

use App\DTOs\DiscussionDTO;
use App\DTOs\SearchDTO;
use App\DTOs\InvitationDTO;
use App\DTOs\MessageDTO;
use App\Events\DeleteDiscussion;
use App\Events\DeleteDiscussionMessage;
use App\Events\NewDiscussion;
use App\Events\NewDiscussionMessage;
use App\Events\NewDiscussionParticipant;
use App\Events\NewDiscussionPendingParticipant;
use App\Events\RemoveDiscussionParticipant;
use App\Events\RemoveDiscussionPendingParticipant;
use App\Events\UpdatedDiscussionParticipant;
use App\Events\UpdateDiscussionMessage;
use App\Events\UpdateDiscussion;
use App\Exceptions\DiscussionException;
use App\Notifications\DiscussionContributionResponseNotification;
use App\Notifications\DiscussionInvitationNotification;
use App\Notifications\DiscussionInvitationResponseNotification;
use App\Notifications\DiscussionJoinNotification;
use App\Notifications\DiscussionJoinRequestNotification;
use App\Notifications\DiscussionJoinResponseNotification;
use App\Notifications\NewDiscussionMessageNotification;
use App\Notifications\RemoveDiscussionParticipantNotification;
use App\Notifications\UpdateDiscussionParticipantNotification;
use App\Traits\InvitationTrait;
use App\Traits\ParticipationServiceTrait;
use App\Traits\ServiceTrait;
use App\User;
use App\YourEdu\Discussion;
use App\YourEdu\Profile;
use App\YourEdu\Request;
use Illuminate\Support\Str;
use \Debugbar;
use Illuminate\Support\Facades\Notification;

class DiscussionService
{
    use ServiceTrait,
        InvitationTrait,
        ParticipationServiceTrait;

    const PAGINATION = 10;

    const MAX_NUMBER_OF_FILES = 3;

    public function createDiscussion(DiscussionDTO $discussionDTO)
    {
        $discussionDTO = $this->setRaisedby($discussionDTO);

        $discussion = $this->addDiscussion($discussionDTO);

        $this->checkDiscussion($discussion);
        
        $discussionDTO = $discussionDTO->withDiscussion($discussion);

        $this->checkFiles($discussionDTO);

        $discussion = $this->addFiles($discussionDTO);

        $discussion = $this->addAttachmentsToDiscussion($discussionDTO);

        $discussionDTO->methodType = 'created';
        $this->broadcastDiscussion($discussionDTO);

        return $this->withLoadedRelationships($discussion);
    }

    private function checkNumberOfFiles($discussionDTO)
    {
        $itemFilesDTO = (new FileService)->countPossibleItemFiles(
            $discussionDTO->discussion,
            $discussionDTO
        );
        
        if ($itemFilesDTO->totalFiles() <= self::MAX_NUMBER_OF_FILES) {
            return;
        }

        $maxFiles = self::MAX_NUMBER_OF_FILES;
        $this->throwDiscussionException(
            message: "sorry 😞, you cannot have more than {$maxFiles} files for a discussion.",
            data: $discussionDTO
        );
    }

    private function checkFiles($discussionDTO)
    {
        $this->checkNumberOfFiles($discussionDTO);

        // $this->checkTypeOfFiles($discussionDTO);

        // $this->checkDurationOfFiles($discussionDTO);

        // $this->checkSizeOfFiles($discussionDTO);
    }

    private function addAttachmentsToDiscussion($discussionDTO)
    {
        foreach ($discussionDTO->attachments as $attachment) {
            
            (new AttachmentService())->attach(
                $discussionDTO->raisedby,
                $discussionDTO->discussion,
                $this->getModel($attachment->type, $attachment->typeId)
            );
        }

        return $discussionDTO->discussion->refresh();
    }

    private function addFiles($discussionDTO)
    {
        foreach ($discussionDTO->files as $file) {
            FileService::createAndAttachFiles(
                account: $discussionDTO->raisedby,
                file: $file,
                item: $discussionDTO->discussion
            );
        }

        return $discussionDTO->discussion->refresh();
    }

    private function checkDiscussion($discussion)
    {
        if (is_not_null($discussion)) {
            return;
        }

        $this->throwDiscussionException(
            message: "sorry 😞, creation of discussion failed.",
        );
    }

    private function addDiscussion($discussionDTO)
    {
        return $discussionDTO->raisedby->discussions()->create([
            'title' => $discussionDTO->title,
            'preamble' => $discussionDTO->preamble,
            'restricted' => $discussionDTO->restricted,  
            'type' => Str::upper($discussionDTO->type),
            'allowed' => Str::upper($discussionDTO->allowed),
        ]);
    }

    private function setRaisedby($discussionDTO)
    {
        if (is_not_null($discussionDTO->discussion)) {
            return $discussionDTO->withRaisedby(
                $discussionDTO->discussion->getAdmin($discussionDTO->userId)
            );
        }

        if (is_not_null($discussionDTO->raisedby)) {
            return $discussionDTO;
        }

        return $discussionDTO->withRaisedby(
            $this->getModel($discussionDTO->account, $discussionDTO->accountId)
        );
    }

    private function withLoadedRelationships($discussion)
    {
        return $discussion->load(['likes','messages','comments','flags','beenSaved',
            'attachments','participants','raisedby.profile']);
    }

    public function getMessages(DiscussionDTO $discussionDTO)
    {
        $discussionDTO = $this->setDiscussion($discussionDTO);

        if (! $discussionDTO->discussion->restricted) {
            return $discussionDTO->discussion->getPaginatedMessages();
        }

        if ($discussionDTO->discussion->isNotAdmin($discussionDTO->userId)) {
            return $discussionDTO->discussion->getPaginatedAcceptedMessages();
        }

        return $discussionDTO->discussion
            ->getPaginatedMessagesByState(Str::upper($discussionDTO->state));
    }

    private function ensureIsAdminOfDiscussion($discussionDTO)
    {
        if (! $discussionDTO->main) {
            return;
        }

        if ($discussionDTO->discussion->isAdmin($discussionDTO->userId)) {
            return;
        }
        
        $this->throwDiscussionException(
            message: 'you are not authorized to perform this action on this discussion',
            data: $discussionDTO
        );
    }

    private function putDiscussion($discussionDTO)
    {
        $discussionDTO->discussion->update([
            'title' => $discussionDTO->title,
            'type' => Str::upper($discussionDTO->type),
            'preamble' => $discussionDTO->preamble,
            'allowed' => Str::upper($discussionDTO->allowed),
            'restricted' => $discussionDTO->restricted,
        ]);

        return $discussionDTO->discussion->refresh();
    }
    
    public function updateDiscussion(DiscussionDTO $discussionDTO)
    {
        $discussion = $this->getModel('discussion', $discussionDTO->discussionId);
        
        $discussionDTO = $discussionDTO->withDiscussion($discussion);
        
        $this->ensureIsAdminOfDiscussion($discussionDTO);
        
        $this->checkFiles($discussionDTO);

        $discussionDTO = $this->setRaisedby($discussionDTO);
        
        $discussion = $this->putDiscussion($discussionDTO);

        $discussion = $this->addFiles($discussionDTO);

        $discussion = $this->deleteFiles($discussionDTO);

        $discussion = $this->addAttachmentsToDiscussion($discussionDTO);

        $discussion = $this->removeAttachmentsFromDiscussion($discussionDTO);

        $discussionDTO->methodType = 'updated';
        $this->broadcastDiscussion($discussionDTO);
        
        return $this->withLoadedRelationships($discussion);
    }

    private function removeAttachmentsFromDiscussion(DiscussionDTO $discussionDTO)
    {
        foreach ($discussionDTO->removedAttachments as $attachment) {
            (new AttachmentService)->detach(
                attachable: $discussionDTO->discussion,
                attach: $this->getModel($attachment->item, $attachment->itemid)
            );
        }

        return $discussionDTO->discussion->refresh();
    }

    private function deleteFiles(DiscussionDTO $discussionDTO)
    {
        foreach ($discussionDTO->removedFiles as $file) {
            FileService::deleteAndUnattachFiles(
                $file,
                $discussionDTO->discussion
            );
        }

        return $discussionDTO->discussion->refresh();
    }

    private function setDiscussion($discussionDTO)
    {
        if (is_not_null($discussionDTO->discussion)) {
            return $discussionDTO;
        }

        return $discussionDTO->withDiscussion(
            $this->getModel('discussion', $discussionDTO->discussionId)
        );
    }

    private function getDiscussionParticipantAccountUsingUserId($discussion, $userId)
    {
        if ($discussion->isOwner($userId)) {
            return $discussion->raisedby;
        }

        $account = $discussion->getParticipantAccountUsingUserId($userId);

        if (is_not_null($account)) {
            return $account;
        }

        $this->throwDiscussionException(
            message: "sorry 😞, you are not a participant of this discussion",
            data: [$discussion, $userId]
        );
    }
    
    public function sendMessage(MessageDTO $messageDTO)
    {
        $discussion = $this->getModel($messageDTO->item, $messageDTO->itemId);
        
        $messageDTO = $messageDTO->withFromable(
            $this->getDiscussionParticipantAccountUsingUserId($discussion, $messageDTO->userId)
        );
        
        $messageDTO = $messageDTO->withMessageable($discussion);

        $messageDTO->state = $this->getNewMessageState($messageDTO);

        $message = (new MessageService())->createMessage($messageDTO);

        $message = $this->withLoadedMessageRelationships($message);

        $messageDTO = $messageDTO->withMessage($message);

        $this->sendMessageRequest($messageDTO);

        if ($message->state === 'ACCEPTED') {
            
            $messageDTO->methodType = 'createdMessage';
            $this->broadcastDiscussion($messageDTO);
        }

        return $message->messageable->restricted ? null : $message;
    }

    private function getNewMessageState($messageDTO)
    {
        if (! $messageDTO->messageable->restricted) {
            return 'accepted';
        }

        if ($messageDTO->messageable->isAdmin($messageDTO->userId)) {
            return 'accepted';
        }

        return 'pending';
    }

    private function sendMessageRequest($messageDTO)
    {
        if (! $messageDTO->messageable->restricted) {
            return;
        }

        $request = (new RequestService)->createRequest(
            from: $messageDTO->fromable,
            to: $messageDTO->messageable->raisedby,
            requestable: $messageDTO->message
        );

        $this->sendNotification(
            User::find($this->getDiscussionAdminIds($messageDTO->messageable)),
            $request,
        );
    }

    private function sendNotification($users, $item) 
    {
        if (is_null($users)) {
            return;
        }

        $notification = $this->getNotification($item);

        if (is_null($notification)) {
            return;
        }

        Notification::send($users, $notification);
    }

    private function getNotification($item)
    {
        if (method_exists($item, 'isMessageRequest')) {
            return new NewDiscussionMessageNotification($item);
        }

        if ($item->methodType === 'invitationResponse') {
            return new DiscussionInvitationResponseNotification($item);
        }

        if ($item->methodType === 'createdMessage') {
            return new DiscussionContributionResponseNotification($item);
        }

        if ($item->methodType === 'updateParticipant') {
            return new UpdateDiscussionParticipantNotification($item);
        }

        if ($item->methodType === 'deleteParticipant') {
            return new RemoveDiscussionParticipantNotification($item);
        }

        if ($item->methodType === 'inviteParticipant') {
            return new DiscussionInvitationNotification($item);
        }

        if ($item->methodType === 'joinResponse') {
            return new DiscussionJoinResponseNotification($item);
        }

        if ($item->methodType === 'joinRequest') {
            return new DiscussionJoinRequestNotification($item);
        }
        
        if ($item->methodType === 'joinDiscussion') {
            return new DiscussionJoinNotification($item);
        }
        
        return null;
    }

    private function checkMessageDeletionAuthorization($messageDTO)
    {
        if ($messageDTO->action !== 'delete' &&
            $messageDTO->message->messageable->isAdmin($messageDTO->userId)) {
            return;
        }

        if ($messageDTO->action === 'self' && 
            $messageDTO->message->messageable->isParticipant($messageDTO->userId)) {
            return;
        }

        if ($messageDTO->action === 'delete' && 
            $messageDTO->message->isFromable($messageDTO->userId) &&
            $messageDTO->message->messageable->isParticipant($messageDTO->userId)) {
            return;
        }

        $this->throwDiscussionException(
            message: "sorry 😞, you are not authorized to perform this action",
            data: $messageDTO
        );
    }

    public function deleteMessage(MessageDTO $messageDTO)
    {
        $messageDTO = $messageDTO->withMessage(
            $this->getModel('message', $messageDTO->messageId)
        );

        $this->checkMessageDeletionAuthorization($messageDTO);

        // $this->deleteMessageQuestions($messageDTO);

        $messageDTO->requireAuthorization = false;
        (new MessageService)->deleteMessage($messageDTO);

        $messageDTO->methodType = 'deletedMessage';
        if (in_array($messageDTO->action, ['all', 'self'])) {
            $messageDTO->methodType = 'updatedMessage';
        }

        $this->broadcastDiscussion($messageDTO);

        if ($messageDTO->action === 'delete') {
            return null;
        }

        return $messageDTO->message->refresh();
    }

    public function contributionResponse(MessageDTO $messageDTO)
    {
        $message = $this->getModel('message', $messageDTO->messageId);
        
        $this->ensureIsAdminOfDiscussion(
            DiscussionDTO::new()
                ->withDiscussion($message->discussion())
                ->addData(userId: $messageDTO->userId, main: true)
        );

        $this->checkMessageState($messageDTO);

        $messageDTO = $messageDTO->withMessage($message);

        $this->updateRequestState($messageDTO);

        $message->state = Str::upper($messageDTO->action);
        $message->save();

        if ($messageDTO->action !== 'accepted') {
            return null;
        }

        $messageDTO = $messageDTO->withMessage(
            $this->withLoadedMessageRelationships($message)
        );

        $messageDTO->methodType = 'createdMessage';
        $this->broadcastDiscussion($messageDTO);

        $this->sendNotification(
            $message->fromable->user,
            $messageDTO
        );

        return $message;
    }

    private function checkMessageState($messageDTO)
    {
        if (in_array($messageDTO->action, ['accepted', 'declined'])) {
            return;
        }

        $this->throwDiscussionException(
            message: "sorry 😞, {$messageDTO->action} is not a valid response",
            data: $messageDTO
        );
    }

    private function withLoadedMessageRelationships($message)
    {
        return $message->load(['images','videos','audios','files','fromable.profile']);
    }

    private function updateRequestState($messageDTO)
    {
        $request = Request::query()
            ->whereMessageRequest()
            ->wherePending()
            ->whereSentTo($messageDTO->message->messageable->raisedby)
            ->first();
            
        if (is_null($request)) {
            return;
        }

        $request->state = Str::upper($messageDTO->action);
        $request->save();
    }

    public function getParticipants(DiscussionDTO $discussionDTO)
    {
        $this->getModel('discussion',$discussionDTO->discussionId);

        if (is_null($discussionDTO->state)) {
            $discussionDTO->state = 'active';
        }

        $participantsProfiles = Profile::query()
            ->wherePartOfDiscussion(
                $discussionDTO->discussionId, 
                $discussionDTO->state
            )
            ->orderBy('name')
            ->paginate(self::PAGINATION);

        return $participantsProfiles;
    }

    private function getDiscussionAdminIds($discussion)
    {
        $ids = $discussion->participants->pluck('user_id');
        $ids->push(...$discussion->raisedby->getAuthorizedIds());

        return $ids;
    }
    
    public function updateDiscussionParticipant(DiscussionDTO $discussionDTO)
    {
        $participant = $this->getParticipant($discussionDTO);

        $discussionDTO = $discussionDTO->withParticipant($participant);
        
        $discussionDTO = $discussionDTO->withDiscussion($participant->participation);
        
        $this->ensureIsAdminOfDiscussion($discussionDTO);

        $participant = $this->updateParticipantStateUsingAction($discussionDTO);

        $discussionDTO->methodType = 'updateParticipant';

        $this->broadcastDiscussion($discussionDTO);

        $this->sendNotification(
            $participant->user,
            $discussionDTO
        );

        return $participant;
    }

    private function getParticipant($discussionDTO)
    {
        if (is_null($discussionDTO->discussion)) {
            return $this->getModel('participant', $discussionDTO->participantId);
        }

        $participant = $discussionDTO->discussion->getParticipant($discussionDTO->participantId);

        if (is_not_null($participant)) {
            return $participant;
        }

        $this->throwDiscussionException(
            message: "sorry 😞, participant with id {$discussionDTO->participantId} not a participant of the discussion with title: {$discussionDTO->discussion->title}",
            data: $discussionDTO
        );
    }
    
    public function deleteDiscussionParticipant(DiscussionDTO $discussionDTO)
    {
        $participant = $this->getParticipant($discussionDTO);

        $discussionDTO = $discussionDTO->withParticipant($participant);

        $discussionDTO = $discussionDTO->withDiscussion($participant->participation);
        
        if ($participant->accountable->isNotAuthorizedUser($discussionDTO->userId)) {
            
            $this->ensureIsAdminOfDiscussion($discussionDTO);
        }

        $participant->delete();

        $discussionDTO->methodType = 'deleteParticipant';

        $this->broadcastDiscussion($discussionDTO);

        $this->sendNotification(
            $this->getDiscussionParticipantOrAdminsUsers($discussionDTO),
            $discussionDTO
        );
    }

    private function getDiscussionParticipantOrAdminsUsers($discussionDTO)
    {
        if ($discussionDTO->participant->accountable->isNotAuthorizedUser($discussionDTO->userId)) {
            return $discussionDTO->participant->accountable->user;
        }

        return $this->getDiscussionAdminsUsers($discussionDTO->discussion);
    }

    private function getDiscussionParticipantAndAdminsUsers($discussionDTO)
    {
        $users = $discussionDTO->discussion->participants()
            ->with(['accountable.user'])
            ->get()
            ->pluck('accountable.user');

        return $users->merge(
            $this->getDiscussionAdminsUsers($discussionDTO->discussion)
        )->unique();
    }

    private function getDiscussionAdminsUsers($discussion)
    {
        return User::find($this->getDiscussionAdminIds($discussion));
    }

    private function getDiscussionAdminsId($discussion)
    {
        $array = $discussion->getAdminParticipants()->pluck('user_id');

        $array[] = $discussion->raisedby->user_id;

        return $array;
    }

    public function discussionSearch(SearchDTO $searchDTO)
    {
        return $this->profileSearch($searchDTO);
    }

    public function inviteParticipant(InvitationDTO $invitationDTO)
    {
        $invitationDTO = $this->setItemModel($invitationDTO);
        
        $invitationDTO = $this->setInvitee($invitationDTO);

        $discussionDTO = DiscussionDTO::new()
            ->withDiscussion($invitationDTO->itemModel)
            ->withInvitationDTO($invitationDTO)
            ->addData(
                userId: $invitationDTO->userId, 
                main: true, 
                action: 'pending'
            );

        $this->ensureAccountUserDoesHaveParticipatingAccount(
            item: $discussionDTO->discussion,
            account: $invitationDTO->invitee
        );

        $this->ensureIsAdminOfDiscussion($discussionDTO);

        $this->ensureAccountHasAllowedType($discussionDTO);

        $request = $this->createInviteRequest($invitationDTO);

        $invitationDTO = $invitationDTO->withRequest($request);

        $participant = $this->createParticipant(
            $invitationDTO->itemModel,
            $invitationDTO->invitee
        );

        $discussionDTO = $discussionDTO->withParticipant($participant);
        $participant = $this->updateParticipantStateUsingAction($discussionDTO);

        $invitationDTO->methodType = 'inviteParticipant';
        $discussionDTO->methodType = 'pendingParticipant';

        $this->broadcastDiscussion(
            $discussionDTO
        );

        $this->sendNotification(
            $invitationDTO->invitee->user,
            $invitationDTO
        );

        return $request;
    }

    private function checkRequestType($request)
    {
        if ($request->isDiscussionRequest()) {
            return;
        }

        $this->throwDiscussionException(
            message: "request with id {$request->id} is not related to a discussion",
            data: $request
        );
    }

    private function throwDiscussionException($message, $data = null)
    {
        throw new DiscussionException(
            message: $message,
            data: $data
        );
    }

    public function joinResponse(InvitationDTO $invitationDTO)
    {
        $request = $this->getModel('request',$invitationDTO->requestId);
        
        $this->checkRequestType($request);
        
        $this->validateAction($invitationDTO);

        $discussionDTO = DiscussionDTO::new()
            ->withDiscussion($request->requestable)
            ->addData(
                main: true, 
                userId: $invitationDTO->userId,
                action: 'active'
            );

        $this->ensureIsAdminOfDiscussion($discussionDTO);

        $request->state = Str::upper($invitationDTO->action);
        $request->save();

        $participant = $this->getJoinRequestParticipant($discussionDTO, $request);

        $invitationDTO->methodType = 'removePendingParticipant';
        $invitationDTO->participantId = $participant->id;
        $this->broadcastDiscussion($invitationDTO);

        $invitationDTO->methodType = 'joinResponse';
        $this->sendNotification(
            $request->requestfrom->user,
            $invitationDTO,
        );

        if ($invitationDTO->action === 'declined') {
            
            $participant->delete();

            return null;
        }

        $participant = $this->updateParticipantStateUsingAction(
            $discussionDTO->withParticipant($participant)
        );

        $discussionDTO->methodType = 'joinDiscussion';

        $this->broadcastDiscussion($discussionDTO);

        $invitationDTO =  $invitationDTO->withRequest($request);
        
        return $participant;
    }

    private function validateAction($invitationDTO)
    {
        if (in_array($invitationDTO->action, ['accepted', 'declined'])) {
            return;
        }

        $this->throwDiscussionException(
            message: "sorry 😞, {$invitationDTO->action} is not a valid response",
            data: $invitationDTO
        );
    }
    
    public function invitationResponse(InvitationDTO $invitationDTO)
    {
        $request = $this->getModel('request',$invitationDTO->requestId);
        
        $invitationDTO = $invitationDTO->withRequest($request);

        $this->checkRequestOwnership($invitationDTO);

        $this->checkRequestType($request);

        $this->validateAction($invitationDTO);

        $request->state = Str::upper($invitationDTO->action);
        $request->save();

        if ($invitationDTO->action === 'declined') {
            return null;
        }

        $participant = $this->createParticipant(
            $request->requestable, 
            $request->requestto
        );

        $this->broadcastDiscussion(
            DiscussionDTO::new()
                ->addData(methodType: 'joinDiscussion', main: true)
                ->withParticipant($participant)
        );

        $invitationDTO = $invitationDTO->withRequest($request);

        $invitationDTO->methodType = 'invitationResponse';
        $this->sendNotification(
            $this->getDiscussionAdminsUsers($request->requestable),
            $invitationDTO,
        );

        return $participant;
    }

    private function deleteDiscussionFiles(Discussion $discussion)
    {
        FileService::deleteYourEduItemFiles($discussion);
    }
    
    public function deleteDiscussion(DiscussionDTO $discussionDTO)
    {
        $discussion = $this->getModel('discussion', $discussionDTO->discussionId);
        
        $discussionDTO = $discussionDTO->withDiscussion($discussion);

        $this->ensureIsAdminOfDiscussion($discussionDTO);

        $discussionDTO = $this->setRaisedby($discussionDTO);

        $this->deleteDiscussionFiles($discussion);

        $discussion->delete();

        $discussionDTO->methodType = 'deleted';
        $this->broadcastDiscussion($discussionDTO);
    }

    private function broadcastDiscussion($dto)
    {
        if (property_exists($dto, 'main') && ! $dto->main) {
            return;
        }

        $event = $this->getEvent($dto);

        if (is_null($event)) {
            return;
        }
        
        broadcast($event)->toOthers();
    }

    private function getEvent($dto)
    {
        if ($dto->methodType === 'created') {
            return new NewDiscussion($dto);
        }

        if ($dto->methodType === 'updated') {
            return new UpdateDiscussion($dto);
        }

        if ($dto->methodType === 'deleted') {
            return new DeleteDiscussion($dto);
        }
        
        if ($dto->methodType === 'createdMessage') {
            return new NewDiscussionMessage($dto);
        }

        if ($dto->methodType === 'updatedMessage') {
            return new UpdateDiscussionMessage($dto);
        }

        if ($dto->methodType === 'deletedMessage') {
            return new DeleteDiscussionMessage($dto);
        }

        if ($dto->methodType === 'removePendingParticipant') {
            return new RemoveDiscussionPendingParticipant($dto);
        }

        if ($dto->methodType === 'pendingParticipant') {
            return new NewDiscussionPendingParticipant($dto);
        }

        if ($dto->methodType === 'joinRequest') {
            return new NewDiscussionPendingParticipant($dto);
        }

        if ($dto->methodType === 'updateParticipant') {
            return new UpdatedDiscussionParticipant($dto);
        }

        if ($dto->methodType === 'joinDiscussion') {
            return new NewDiscussionParticipant($dto);
        }

        if ($dto->methodType === 'deleteParticipant') {
            return new RemoveDiscussionParticipant($dto);
        }

        return null;
    }

    private function ensureAccountHasAllowedType($dto)
    {
        if (is_null($dto->invitationDTO)) {
            return;
        }

        if ($dto->discussion->allowed === 'ALL') {
            return;
        }

        $account = $dto->invitationDTO->joiner ?
            $dto->invitationDTO->joiner : $dto->invitationDTO->invitee;

        if ($dto->discussion->allowed === Str::upper("{$account->accountType}s")) {
            return;
        }

        $type = strtolower(substr_replace($dto->discussion->allowed, '', -1));
        $this->throwDiscussionException(
            message: "sorry 😞, {$account->accountType} account is not allowed. please use a {$type} account",
            data: $dto
        );
    }

    public function joinDiscussion(InvitationDTO $invitationDTO)
    {
        $invitationDTO = $this->setItemModel($invitationDTO);

        $invitationDTO = $this->setJoiner($invitationDTO);

        $discussionDTO = DiscussionDTO::new()
            ->addData(
                userId: $invitationDTO->userId, 
                main: true, 
                methodType: "joinDiscussion"
            )
            ->withInvitationDTO($invitationDTO)
            ->withDiscussion($invitationDTO->itemModel);

        $this->ensureAccountUserDoesHaveParticipatingAccount(
            item: $discussionDTO->discussion,
            account: $invitationDTO->joiner
        );

        $this->ensureNotAdminOrParticipant($discussionDTO);

        $this->ensureAccountHasAllowedType($discussionDTO);

        if ($invitationDTO->itemModel->type === 'PRIVATE') {
            
            return $this->sendJoinRequest($invitationDTO);
        }

        $participant = $this->createParticipant(
            $invitationDTO->itemModel,
            $invitationDTO->joiner
        );

        $this->sendNotification(
            $this->getDiscussionAdminsUsers($discussionDTO->discussion),
            $discussionDTO
        );
        
        $this->broadcastDiscussion(
            $discussionDTO->withParticipant($participant)
        );

        return $participant;
    }

    private function ensureNotAdminOrParticipant($discussionDTO)
    {
        if ($discussionDTO->discussion->isNotOwner($discussionDTO->userId) &&
            $discussionDTO->discussion->isNotParticipant($discussionDTO->userId)) {
            return;
        }

        $this->throwDiscussionException(
            message: "sorry 😞, an admin or participant cannot perform this action.",
            data: $discussionDTO
        );
    }

    private function ensureNoPendingJoinRequests($invitationDTO)
    {
        $request = Request::query()
            ->whereDiscussionRequest()
            ->wherePending()
            ->whereRequestFromUser($invitationDTO->userId)
            ->exists();

        if (! $request) {
            return;
        }

        $this->throwDiscussionException(
            message: "sorry 😞, you already have a pending request to join this discussion",
            data: $invitationDTO
        );
    }

    public function sendJoinRequest($invitationDTO)
    {
        $this->ensureNoPendingJoinRequests($invitationDTO);

        $request = $this->createJoinRequest($invitationDTO);

        $invitationDTO = $invitationDTO->withRequest($request);

        $invitationDTO->methodType = 'joinRequest';

        $this->sendNotification(
            $this->getDiscussionAdminsUsers($request->requestable),
            $invitationDTO,
        );

        $participant = $this->createParticipant(
            $request->requestable,
            $request->requestfrom
        );

        $discussionDTO = DiscussionDTO::new()
            ->withParticipant($participant)
            ->addData(
                main: true, 
                userId: $invitationDTO->userId, 
                methodType: 'joinRequest',
                action: 'pending'
            );

        $this->updateParticipantStateUsingAction($discussionDTO);

        $this->broadcastDiscussion($discussionDTO);

        return null;
    }
}

?>