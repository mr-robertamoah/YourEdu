<?php

namespace App\DTOs;

use App\Traits\DTOTrait;
use App\User;
use App\YourEdu\Request as YourEduRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class InvitationDTO
{
    use DTOTrait;

    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $type = null;
    public ?string $notificationId = null;
    public ?string $participantId = null;
    public ?string $item = null;
    public ?string $itemId = null;
    public ?string $requestId = null;
    public ?string $action = null;
    public ?string $methodType = null;
    public ?YourEduRequest $request = null;
    public ?Model $joiner = null;
    public ?Model $inviter = null;
    public ?Model $invitee = null;
    public ?Model $itemModel = null;
    public ?Collection $users = null;

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->action = $request->response ?: $request->action;
        $self->requestId = $request->requestId;
        $self->notificationId = $request->notificationId;
        $self->type = $request->type;
        $self->item = $request->discussionId ? 'discussion' : 'assessment';
        $self->itemId = $request->discussionId ?: $request->assessmentId;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->userId = $request->user()?->id;

        return $self;
    }

    public function withRequest(YourEduRequest $request)
    {
        $clone = clone $this;

        $clone->request = $request;

        return $clone;
    }

    public function withJoiner(Model $joiner)
    {
        $clone = clone $this;

        $clone->joiner = $joiner;

        return $clone;
    }

    public function withInviter(Model $inviter)
    {
        $clone = clone $this;

        $clone->inviter = $inviter;

        return $clone;
    }

    public function withInvitee(Model $invitee)
    {
        $clone = clone $this;

        $clone->invitee = $invitee;

        return $clone;
    }

    public function withItemModel(Model $itemModel)
    {
        $clone = clone $this;

        $clone->itemModel = $itemModel;

        return $clone;
    }
}
