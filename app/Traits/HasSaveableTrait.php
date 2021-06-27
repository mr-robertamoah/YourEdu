<?php

namespace App\Traits;

use App\YourEdu\Save;

trait HasSaveableTrait
{
    public function beenSaved()
    {
        return $this->morphMany(Save::class, 'saveable');
    }

    public function saves()
    {
        return $this->beenSaved;
    }
}
