<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;

class ActivityTrackDTO
{
    public ?Model $activity = null;
    public ?Model $activityfor = null;
    public ?Model $performedby = null;
    public ?string $action = null;

    public static function new()
    {
        return new static;
    }

    public static function createFromData
    (
        $action = null,
        $activityfor = null,
        $performedby = null,
        $activity = null,
    )
    {
        $self = new static;

        $self->action = $action;
        $self->activity = $activity;
        $self->activityfor = $activityfor;
        $self->performedby = $performedby;

        return $self;
    }
}
