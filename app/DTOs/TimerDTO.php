<?php

namespace App\DTOs;

use App\Traits\DTOTrait;
use App\YourEdu\Timer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TimerDTO
{
    use DTOTrait;

    public ?string $timerId = null;
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $item = null;
    public ?string $itemId = null;
    public ?Model $addedby = null;
    public ?Model $timeable = null;
    public ?Timer $timer = null;
    public ?Carbon $time = null;

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->time = new Carbon($request->time);
        $self->timerId = $request->timerId;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->item = $request->item;
        $self->itemId = $request->itemId;
        $self->userId = $request->user()->id;

        return $self;
    }
}
