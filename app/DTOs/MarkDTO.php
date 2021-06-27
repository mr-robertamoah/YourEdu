<?php

namespace App\DTOs;

use App\Traits\DTOTrait;
use Illuminate\Database\Eloquent\Model;

class MarkDTO
{
    use DTOTrait;

    public ?string $itemId = null;
    public ?string $item = null;
    public ?string $accountId = null;
    public ?string $account = null;
    public ?string $score = null;
    public ?string $scoreOver = null;
    public ?string $remark = null;
    public ?string $state = null;
    public bool $chat = false;
    public ?Model $markable = null;
    public ?Model $markedby = null;

    public static function new()
    {
        return new static;
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
        $static->score = $score;
        $static->scoreOver = $scoreOver;
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
