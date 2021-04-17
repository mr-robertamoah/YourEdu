<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SaveDTO
{
    public ?Model $saveable = null;
    public ?Model $savedby = null;
    public ?Model $save = null;
    public ?string $userId = null;
    public ?string $item = null;
    public ?string $itemId = null;
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $saveId = null;
    public ?string $adminId = null;
    public ?string $method = null;
    public ?string $methodType = null;

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->saveId = $request->saveId;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->item = $request->item;
        $self->itemId = $request->itemId;
        $self->adminId = $request->adminId;
        $self->userId = $request->user()?->id;

        return $self;
    }

    public function withSavedby($savedby)
    {
        $clone = clone $this;

        $clone->savedby = $savedby;

        return $clone;
    }

    public function withSaveable($saveable)
    {
        $clone = clone $this;

        $clone->saveable = $saveable;

        return $clone;
    }

    public function withSave($save)
    {
        $clone = clone $this;

        $clone->save = $save;

        return $clone;
    }
}
