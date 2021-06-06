<?php

namespace App\Traits;

use App\YourEdu\Commission;

trait AccountCommissionTrait
{
    public function ownedCommissions()
    {
        return $this->morphMany(Commission::class,'ownedby');
    }

    public function addedCommissions()
    {
        return $this->morphMany(Commission::class,'addedby');
    }
}
