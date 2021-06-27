<?php

namespace App\DTOs;

use App\Traits\DTOTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class DiscussionDTO
{
    use DTOTrait;

    public ?string $discussionId = null;
    public ?string $participantId = null;
    public array $files = [];
    public array $removedFiles = [];
    public array $attachments = [];
    public array $removedAttachments = [];
    public ?Model $participant = null;
    public ?InvitationDTO $invitationDTO = null;
    public ?Model $discussion = null;
    public ?Model $raisedby = null;
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $title = null;
    public ?string $preamble = null;
    public ?string $type = null;
    public ?string $allowed = null;
    public ?string $action = null;
    public ?string $state = null;
    public bool $restricted = false;
    public bool $main = false;

    public static function createFromData($data)
    {
        $self = new static;

        $self->title = $data->title ?? null;
        $self->preamble = $data->preamble ?? null;
        $self->restricted = $data->restricted ?? false;
        $self->type = $data->type ?? 'PRIVATE';
        $self->allowed = $data->allowed ?? 'ALL';

        return $self;
    }

    public static function createFromRequest(
        Request $request,
        bool $main = false
    ) {
        $self = new static;

        $self->restricted = $request->restricted ? json_decode($request->restricted) : false;
        $self->action = $request->action;
        $self->allowed = $request->allowed;
        $self->discussionId = $request->discussionId;
        $self->participantId = $request->participantId;
        $self->type = $request->type;
        $self->title = $request->title;
        $self->preamble = $request->preamble;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->userId = $request->user()?->id;
        $self->main = $main;
        $self->attachments = $request->attachments ?
            ModelDTO::createFromArray(
                json_decode($request->attachments)
            ) : [];
        $self->removedAttachments = $request->removedAttachments ?
            ModelDTO::createFromArray(
                json_decode($request->removedAttachments)
            ) : [];
        $self->files = $request->hasFile('files') ?
            $request->file('files') : [];
        $self->removedFiles = static::getRemovedFiles($request);

        return $self;
    }

    public static function getRemovedFiles($request)
    {
        $files = [];
        if ($request->removedTypeFiles) {
            $files = $request->removedTypeFiles;
        }

        if ($request->removedFiles) {
            $files = $request->removedFiles;
        }

        if (is_string($files)) {
            $files = json_decode($files);
        }

        return FileDTO::createFromArray($files);
    }

    public function withFiles($files)
    {
        if (is_null($files)) {
            return $this;
        }

        if (!is_array($files)) {
            return $this;
        }

        $clone = clone $this;

        $clone->files = $files;

        return $clone;
    }

    public function withRaisedby(Model $raisedby)
    {
        $clone = clone $this;

        $clone->raisedby = $raisedby;

        return $clone;
    }

    public function withDiscussion(Model $discussion)
    {
        $clone = clone $this;

        $clone->discussion = $discussion;

        return $clone;
    }

    public function withInvitationDTO(InvitationDTO $invitationDTO)
    {
        $clone = clone $this;

        $clone->invitationDTO = $invitationDTO;

        return $clone;
    }

    public function withParticipant(Model $participant)
    {
        $clone = clone $this;

        $clone->participant = $participant;

        return $clone;
    }
}
