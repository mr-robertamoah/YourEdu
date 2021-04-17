<?php

namespace App\Traits;

use App\YourEdu\Admission;

trait AdmissionTrait
{
    public function addedAdmissions()
    {
        return $this->morphMany(Admission::class,'addedby');
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }
}
