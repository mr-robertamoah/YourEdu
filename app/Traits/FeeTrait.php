<?php

namespace App\Traits;

use App\YourEdu\Fee;
use App\YourEdu\Feeable;

trait FeeTrait
{
    public function feeables()
    {
        return $this->morphMany(Feeable::class, 'feeable');
    }

    public function fees()
    {
        return $this->morphToMany(Fee::class, 'feeable', 'feeables');
    }
}
