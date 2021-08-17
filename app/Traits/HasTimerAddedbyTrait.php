<?php

namespace App\Traits;

use App\YourEdu\Timer;

trait HasTimerAddedbyTrait
{
    public function timers()
    {
        return $this->morphMany(Timer::class, 'addedby');
    }
}
