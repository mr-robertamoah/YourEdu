<?php

namespace App\DTOs;

use App\YourEdu\Conversation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ConversationDTO
{
    public ?string $name = null;
    public ?string $description = null;
    public ?string $accountType = null;
    public ?UploadedFile $profileImage = null;
    public ?string $userId = null;
    public ?string $conversationId = null;
    public ?string $myAccount = null;
    public ?string $myAccountId = null;
    public ?string $otherAccount = null;
    public ?string $otherAccountId = null;
    public ?Model $myChattingAccount = null;
    public ?Model $otherChattingAccount = null;
    public ?string $methodType = null;
    public ?string $response = null;
    public ?Conversation $conversation = null;
    public array $states = [];

    public static function new()
    {
        return new static;
    }

    public function addData
    (
        $userId = null
    )
    {
        $this->userId = $userId;

        return $this;
    }

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->userId = $request->user()?->id;
        $self->conversationId = $request->conversationId;
        $self->name = $request->name;
        $self->description = $request->description;
        $self->accountType = $request->accountType;
        $self->response = $request->response;
        $self->myAccount = $request->account;
        $self->myAccountId = $request->accountId;
        $self->otherAccount = $request->otherAccount;
        $self->otherAccountId = $request->otherAccountId;

        return $self;
    }

    public function withStates(array $states)
    {
        $clone = clone $this;

        $clone->states = $states;

        return $clone;
    }

    public function withConversation(Conversation $conversation)
    {
        $clone = clone $this;

        $clone->conversation = $conversation;

        return $clone;
    }

    public function withMyChattingAccount(Model $myChattingAccount)
    {
        $clone = clone $this;

        $clone->myChattingAccount = $myChattingAccount;

        return $clone;
    }

    public function withOtherChattingAccount(Model $otherChattingAccount)
    {
        $clone = clone $this;

        $clone->otherChattingAccount = $otherChattingAccount;

        return $clone;
    }
}
