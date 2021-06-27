<?php

namespace App\Traits;

use App\YourEdu\Assessmentable;
use App\YourEdu\Mark;

trait HasMarkedbyTrait
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

    public function marks()
    {
        return $this->morphMany(Mark::class,'markedby');
    }

    public function hasMarkedSubmittedWorkForAssessment($assessmentId)
    {
        return $this->marks()
            ->whereAssessment($assessmentId, "App\\YourEdu\\Work")
            ->exists();
    }

    public function doesntHaveMarkedSubmittedWorkForAssessment($assessmentId) {
        return ! $this->hasMarkedSubmittedWorkForAssessment($assessmentId);
    }
}
