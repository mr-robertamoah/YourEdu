<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class MessageDTO
{
    public ?string $item = null;
    public ?string $itemId = null;
    public ?string $fromAccount = null;
    public ?string $fromAccountId = null;
    public ?string $fromUserId = null;
    public ?Model $message = null;
    public ?string $messageText = null;
    public ?string $action = null;
    public ?string $messageId = null;
    public bool $requireAuthorization = true;
    public ?string $state = null;
    public ?Model $fromable = null;
    public ?Model $toable = null;
    public ?Model $messageable = null;
    public array $files = [];
    public ?string $toAccount = null;
    public ?string $toAccountId = null;
    public ?string $toUserId = null;
    public ?string $method = null;
    
    public static function new()
    {
        return new static;
    }
    
    public function addData
    (
        $item = null,
        $itemId = null,
        $state = null,
        $messageId = null,
        $action = null,
        $requireAuthorization = true,
    )
    {
        $self = clone $this;

        $self->item = $item;
        $self->itemId = $itemId;
        $self->requireAuthorization = $requireAuthorization;
        $self->action = $action;
        $self->messageId = $messageId;
        $self->state = $state;

        return $self;
    }
    
    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->messageId = $request->messageId;
        $self->action = $request->action;
        $self->fromAccount = $request->account;
        $self->fromAccountId = $request->accountId;
        $self->fromUserId = $request->user()?->id;
        $self->messageText = $request->message;
        $self->files = $request->hasFile('files') ? 
            $request->file('files') : [];

        return $self;
    }

    public function addFile(UploadedFile | null $file)
    {
        $clone = clone $this;

        if (is_null($file)) {
            return $clone;
        }
        
        array_push($clone->files, $file);

        return $clone;
    }

    public function withFromable(Model $fromable)
    {
        $clone = clone $this;

        $clone->fromable = $fromable;

        return $clone;
    }

    public function withToable(Model $toable)
    {
        $clone = clone $this;

        $clone->toable =  $toable;

        return $clone;
    }

    public function withMessage(Model $message)
    {
        $clone = clone $this;

        $clone->message =  $message;

        return $clone;
    }

    public function withMessageable(Model $messageable)
    {
        $clone = clone $this;

        $clone->messageable =  $messageable;

        return $clone;
    }
}
