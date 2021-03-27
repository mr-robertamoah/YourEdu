<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class DiscussionDTO
{
    public ?string $discussionId = null;
    public array $files;
    public array $removedFiles;
    public array $attachments;
    public array $removedAttachments;
    public ?Model $discussion = null;
    public ?Model $raisedby = null;
    public ?int $userId = null;
    public string | null $account;
    public string | null $accountId;
    public bool $main = false;
    public ?string $methodType = null;
    
    public static function createFromData
    (
        $discussionId = null,
        $userId = null,
    )
    {
        $self = new static;

        $self->discussionId = $discussionId;
        $self->userId = $userId;
        
        return $self;
    }

    public static function createFromRequest
    (
        Request $request, bool $main = false
    )
    {
        $self = new static;
        
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->userId = (int) $request->user()->id;
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
        $self->removedFiles = $request->removedTypeFiles ?
            FileDTO::createFromArray(
                json_decode($request->removedTypeFiles)
            ) : [];

        return $self;
    }

    public function withRaisedby(Model $raisedby)
    {
        $clone = clone $this;

        $clone->raisedby = $raisedby;

        return $clone;
    }
}
