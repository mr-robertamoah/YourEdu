<?php

namespace App\DTOs;

use App\YourEdu\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class MessageDTO
{
    public ?string $item = null;
    public ?string $itemId = null;
    public ?string $userId = null;
    public string $orderBy = 'created_at';
    public ?string $fromAccount = null;
    public ?string $fromAccountId = null;
    public ?string $fromUserId = null;
    public ?Message $message = null;
    public ?string $messageText = null;
    public ?string $action = null;
    public ?string $messageId = null;
    public bool $requireAuthorization = true;
    public ?string $state = null;
    public ?QuestionDTO $questionDTO = null;
    public ?Model $fromable = null;
    public ?Model $toable = null;
    public ?Model $messageable = null;
    public array $files = [];
    public ?string $toAccount = null;
    public ?string $toAccountId = null;
    public ?string $toUserId = null;
    public ?string $method = null;
    public ?string $methodType = null;
    
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
        $orderBy = '',
        $requireAuthorization = true,
    )
    {
        $self = clone $this;

        $self->item = $item;
        $self->itemId = $itemId;
        $self->requireAuthorization = $requireAuthorization;
        $self->action = $action;
        $self->orderBy = $orderBy;
        $self->messageId = $messageId;
        $self->state = $state;

        return $self;
    }
    
    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->messageId = $request->messageId;
        $self->userId = $request->user()?->id;
        $self->action = $request->action;
        $self->state = $request->state;
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
        if (is_null($file)) {
            return $this;
        }
        
        array_push($this->files, $file);

        return $this;
    }

    public function addFiles($files)
    {
        if (is_null($files) || ! is_array($files)) {
            return $this;
        }

        $this->files = $files;

        return $this;
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

    public function addQuestionData($questionData)
    {
        $clone = clone $this;

        if (is_null($questionData)) {
            return $clone;
        }

        if (is_string($questionData)) {
            $questionData = json_decode($questionData);
        }

        $clone->questionDTO = QuestionDTO::createFromData(
            body: $questionData->body,
            hint: $questionData->hint,
            answerType: $questionData->answerType,
            possibleAnswers: $questionData->possibleAnswers,
            scoreOver: $questionData->scoreOver,
            autoMark: $questionData->autoMark,
            correctPossibleAnswers: $questionData->correctPossibleAnswers,
        );

        return $clone;
    }

    public function addQuestionFiles($files)
    {
        if (is_null($this->questionDTO)) {
            return $this;
        }

        if (is_null($files)) {
            return $this;
        }

        $this->questionDTO->files = $files;

        return $this;
    }
}
