<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;

class AccountToJoinItemDTO
{
    public ?Model $account = null;
    public ?string $message = null;
    public ?string $action = null;
    public ?Model $dashboardItem = null;

    public static function createFromData
    (
        $account = null,
        $dashboardItem = null,
        $action = null,
        $message = null,
    )
    {
        $self = new static;

        $self->action = $action;
        $self->dashboardItem = $dashboardItem;
        $self->account = $account;
        $self->message = $message;

        return $self;
    }
}
