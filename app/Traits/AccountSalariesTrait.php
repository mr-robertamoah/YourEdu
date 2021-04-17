<?php

namespace App\Traits;

use App\YourEdu\Salary;

trait AccountSalariesTrait
{
    public function addedSalaries()
    {
        return $this->morphMany(Salary::class, 'addedby');
    }

    public function ownedSalaries()
    {
        return $this->morphMany(Salary::class, 'ownedby');
    }
}
