<?php

namespace App\Traits;

use App\YourEdu\Assessment;

trait AssessmentTrait
{
    public function assessments()
    {
        return $this->morphToMany(
            Assessment::class, 'assessmentable', 'assessmentables'
        )->withTimestamps();
    }
}