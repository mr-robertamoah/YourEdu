<?php

namespace App\Traits;

use App\YourEdu\Assessmentable;

trait MarkerTrait
{
    public function getMarkerUsingAssessmentId($assessmentId)
    {
        return $this->assessmentables()
            ->whereAssessment($assessmentId)
            ->first();
    }

    public function assessmentables()
    {
        return $this->morphMany(Assessmentable::class, 'assessmentable');
    }
}
