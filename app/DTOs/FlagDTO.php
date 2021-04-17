<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class FlagDTO
{
    public ?Model $flaggable = null;
    public ?Model $flaggedby = null;
    public ?Model $flag = null;
    public ?string $userId = null;
    public ?string $reason = null;
    public ?string $state = null;
    public ?string $item = null;
    public ?string $itemId = null;
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $flagId = null;
    public ?string $adminId = null;
    public ?string $method = null;
    public ?string $methodType = null;

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->flagId = $request->flagId;
        $self->reason = $request->reason;
        $self->state = $request->state;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->item = $request->item;
        $self->itemId = $request->itemId;
        $self->adminId = $request->adminId;
        $self->userId = $request->user()?->id;

        return $self;
    }

    public function withFlaggedby($flaggedby)
    {
        $clone = clone $this;

        $clone->flaggedby = $flaggedby;

        return $clone;
    }

    public function withFlaggable($flaggable)
    {
        $clone = clone $this;

        $clone->flaggable = $flaggable;

        return $clone;
    }

    public function withFlag($flag)
    {
        $clone = clone $this;

        $clone->flag = $flag;

        return $clone;
    }
}
