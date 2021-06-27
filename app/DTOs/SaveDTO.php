<?php

namespace App\DTOs;

use App\Traits\DTOTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SaveDTO
{
    use DTOTrait;

    public ?Model $saveable = null;
    public ?Model $savedby = null;
    public ?Model $save = null;
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
}
