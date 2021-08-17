<?php

namespace App\Traits;

use App\YourEdu\Timer;

trait HasTimeableTrait
{
    public function timers()
    {
        return $this->morphMany(Timer::class, 'timeable');
    }

    public function hasTimerAddedby($addedby)
    {
        return $this->timers()
            ->whereAddedby($addedby)
            ->exists();
    }

    public function hasTimerAddedbyUser($userId)
    {
        return $this->timers()
            ->whereAddedbyUser($userId)
            ->exists();
    }

    public function doesntHaveTimerAddedbyUser($userId)
    {
        return !$this->hasTimerAddedbyUser($userId);
    }

    public function timerAddedby($addedby)
    {
        return $this->timers()
            ->whereAddedby($addedby)
            ->first();
    }

    public function timerAddedbyUser($userId)
    {
        return $this->timers()
            ->whereAddedbyUser($userId)
            ->first();
    }
}
