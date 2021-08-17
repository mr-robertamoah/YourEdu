<?php

namespace App\DTOs;

use App\Traits\DTOTrait;
use App\YourEdu\Mark;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MarkDTO
{
    use DTOTrait;

    public ?string $itemId = null;
    public ?string $item = null;
    public ?string $accountId = null;
    public ?string $account = null;
    public ?float $score = null;
    public ?float $scoreOver = null;
    public ?string $remark = null;
    public ?string $state = null;
    public ?string $markId = null;
    public bool $chat = false;
    public ?Mark $mark = null;
    public ?Model $markable = null;
    public ?Model $markedby = null;

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->item = $request->item;
        $self->itemId = $request->itemId;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->chat = $request->chat ?: false;
        $self->markId = $request->markId;
        $self->score = $request->score ? (float) $request->score : null;
        $self->remark = $request->remark;
        $self->userId = $request->user()?->id;

        return $self;
    }

    public static function createFromData(
        $remark = null,
        $chat = false,
        $scoreOver = null,
        $score = null,
        $account = null,
        $accountId = null,
        $item = null,
        $itemId = null,
        $userId = null,
        $state = null,
    ) {
        $static = new static();

        $static->remark = $remark;
        $static->chat = $chat;
        $static->itemId = $itemId;
        $static->item = $item;
        $static->accountId = $accountId;
        $static->account = $account;
        $static->score = $score ? (float) $score : null;
        $static->scoreOver = $scoreOver ? (float) $scoreOver : null;
        $static->userId = $userId;
        $static->state = $state;

        return $static;
    }

    public function withMarkable(Model $markable)
    {
        $clone = clone $this;

        $clone->markable = $markable;

        return $clone;
    }

    public function withMarkedby(Model $markedby)
    {
        $clone = clone $this;

        $clone->markedby = $markedby;

        return $clone;
    }
}
