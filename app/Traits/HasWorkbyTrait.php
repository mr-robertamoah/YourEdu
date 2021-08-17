<?php

namespace App\Traits;

use App\YourEdu\Work;

trait HasWorkbyTrait
{
    public function works()
    {
        return $this->morphMany(Work::class, 'addedby');
    }

    public function getWorkForAssessment($assessmentId)
    {
        return $this->works()
            ->whereAssessment($assessmentId)
            ->first();
    }

    public function hasASubmittedWorkForAssessment($assessmentId)
    {
        return $this->works()
            ->whereDone()
            ->whereAssessment($assessmentId)
            ->exists();
    }

    public function hasAMarkedSubmittedWorkForAssessmentAndMarkedbyAccount($assessmentId, $account)
    {
        return $this->works()
            ->whereMarksMarkedby($account)
            ->whereDone()
            ->whereAssessment($assessmentId)
            ->exists();
    }

    public function doesntHaveAMarkedSubmittedWorkForAssessmentAndMarkedbyAccount($assessmentId, $account)
    {
        return !$this->hasAMarkedSubmittedWorkForAssessmentAndMarkedbyAccount($assessmentId, $account);
    }

    public function hasAMarkedSubmittedWorkForAssessment($assessmentId)
    {
        return $this->works()
            ->has('marks')
            ->whereDone()
            ->whereAssessment($assessmentId)
            ->exists();
    }

    public function doesntHaveAMarkedSubmittedWorkForAssessment($assessmentId)
    {
        return !$this->hasAMarkedSubmittedWorkForAssessment($assessmentId);
    }

    public function doesntHaveASubmittedWorkForAssessment($assessmentId)
    {
        return !$this->hasASubmittedWorkForAssessment($assessmentId);
    }

    public function hasWorkForAssessment($assessmentId)
    {
        return $this->works()
            ->whereAssessment($assessmentId)
            ->exists();
    }

    public function doesntHaveWorkForAssessment($assessmentId)
    {
        return !$this->hasWorkForAssessment($assessmentId);
    }
}
