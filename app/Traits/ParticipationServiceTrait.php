<?php

namespace App\Traits;

use App\DTOs\SearchDTO;
use App\Services\MarkService;
use App\Services\RequestService;
use App\Services\SearchService;
use App\YourEdu\Assessment;
use App\YourEdu\Discussion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait ParticipationServiceTrait
{
    private function createParticipant(
        Discussion | Assessment $item,
        Model $account
    ) {
        $participant = $item->participants()->create([
            'user_id' => $account->user_id,
            'state' => 'ACTIVE'
        ]);

        $participant->accountable()->associate($account);
        $participant->save();

        return $participant;
    }

    private function updateParticipantStateUsingAction($dto)
    {
        $this->validateParticipantAction($dto);

        $dto->participant->state = Str::upper($dto->action);
        $dto->participant->save();

        return $dto->participant;
    }

    public function validateParticipantAction($dto)
    {
        if (in_array(Str::upper($dto->action), ['ADMIN', 'ACTIVE', 'RESTRICTED', 'BANNED', 'PENDING'])) {
            return;
        }

        $this->throwAccountNotFoundException(
            message: "sorry 😞, the action {$dto->action} is invalid.",
            data: $dto
        );
    }

    private function validateAction($invitationDTO)
    {
        if (in_array($invitationDTO->action, ['accepted', 'declined'])) {
            return;
        }

        $this->throwAccountNotFoundException(
            message: "sorry 😞, {$invitationDTO->action} is not a valid response",
            data: $invitationDTO
        );
    }

    public function checkRequestOwnership($invitationDTO)
    {
        if ($invitationDTO->request->requestto->isAuthorizedUser($invitationDTO->userId)) {
            return;
        }

        $this->throwAccountNotFoundException(
            message: "sorry 😞, you are not authorized to respond to this request",
            data: $invitationDTO
        );
    }

    private function getJoinRequestParticipant($dto, $request)
    {
        $participant = property_exists($dto, 'discussion') ?
            $dto->discussion->getParticipantUsingAccount($request->requestfrom) :
            $dto->assessment->getParticipantUsingAccount($request->requestfrom);

        if (is_not_null($participant)) {
            return $participant;
        }

        $this->throwAccountNotFoundException(
            message: "no participant was found for this request.",
            data: $dto
        );
    }

    private function getInvitationRequestParticipant($dto, $request)
    {
        $participant = property_exists($dto, 'discussion') ?
            $dto->discussion->getParticipantUsingAccount($request->requestto) :
            $dto->assessment->getParticipantUsingAccount($request->requestto);

        if (is_not_null($participant)) {
            return $participant;
        }

        $this->throwAccountNotFoundException(
            message: "no participant was found for this request.",
            data: $dto
        );
    }

    private function createJoinRequest($invitationDTO)
    {
        return (new RequestService)->createRequest(
            from: $invitationDTO->joiner,
            requestable: $invitationDTO->itemModel,
            to: $invitationDTO->itemModel->addedby,
            data: $invitationDTO->type
        );
    }

    private function createInviteRequest($invitationDTO)
    {
        return (new RequestService)->createRequest(
            to: $invitationDTO->invitee,
            from: class_basename_lower($invitationDTO->itemModel) === 'discussion' ?
                $invitationDTO->itemModel->getAdmin($invitationDTO->userId) :
                $invitationDTO->itemModel->addedby,
            requestable: $invitationDTO->itemModel,
            data: $invitationDTO->type
        );
    }

    private function ensureAccountUserDoesHaveParticipatingAccount($item, $account)
    {
        if (is_null($otherAccount = $item->getParticipantAccountUsingUserId($account->user_id))) {
            return;
        }

        $this->throwAccountNotFoundException(
            message: "sorry 😞, you are already participating with your {$otherAccount->accountType} account.",
            data: [$item, $account]
        );
    }

    public function profileSearch(SearchDTO $searchDTO)
    {
        $item = $this->getModel($searchDTO->item, $searchDTO->itemId);

        if ($searchDTO->forMarkers) {
            $searchDTO->accountTypes = MarkService::MARKER_CLASSES;
        }

        $userIdsOfInviteesWithPendingRequests = $item->requests()
            ->when($searchDTO->forMarkers, function ($query) {
                $query->whereMarkerRequest();
            })
            ->whereRequestFromUsers(
                $searchDTO->item === 'discussion' ?
                    $this->getDiscussionAdminsId($item) :
                    [$item->addedby->user_id]
            )
            ->wherePending()
            ->get()
            ->pluck('requestto')
            ->map(function ($item) {
                return $item->user_id;
            })
            ->toArray();

        $searchDTO = $searchDTO->addToExcludedUserIds(
            array_merge($userIdsOfInviteesWithPendingRequests, $item->getUserIds()->toArray())
        );
        $searchDTO = $searchDTO->addToFlaggedbyUserIds(
            $this->getUserAndParenstUserIds($searchDTO)
        );

        return (new SearchService)->profileSearchForHomeItem($searchDTO);
    }
}
