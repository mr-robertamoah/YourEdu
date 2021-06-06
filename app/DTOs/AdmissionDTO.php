<?php

namespace App\DTOs;

use App\YourEdu\Admission;
use Illuminate\Database\Eloquent\Model;

class AdmissionDTO
{
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $state = null;
    public ?string $type = null;
    public ?string $learnerId = null;
    public ?string $schoolId = null;
    public ?string $gradeId = null;
    public ?string $admissionId = null;
    public ?Admission $admission = null;
    public ?Model $school = null;
    public ?Model $grade = null;
    public ?Model $learner = null;
    public ?Model $addedby = null;

    public static function createFromData
    (
        $learnerId = null,
        $schoolId = null,
        $admissionId = null,
        $gradeId = null,
        $account = null,
        $accountId = null,
        $type = null,
        $state = null,
    )
    {
        $self = new static;

        $self->account = $account;
        $self->state = $state ?: 'pending';
        $self->type = $type ?: 'traditional';
        $self->accountId = $accountId;
        $self->learnerId = $learnerId;
        $self->schoolId = $schoolId;
        $self->gradeId = $gradeId;
        $self->admissionId = $admissionId;

        return $self;
    }

    public function withAddedby(Model $addedby)
    {
        $clone = clone $this;

        $clone->addedby = $addedby;

        return $clone;
    }

    public function withSchool(Model $school)
    {
        $clone = clone $this;

        $clone->school = $school;

        return $clone;
    }

    public function withLearner(Model $learner)
    {
        $clone = clone $this;

        $clone->learner = $learner;

        return $clone;
    }

    public function withGrade(Model | null $grade)
    {
        $clone = clone $this;

        if (is_null($grade)) {
            $clone->gradeId = null;
        }
        
        $clone->grade = $grade;

        return $clone;
    }

    public function withAdmission(Model $admission)
    {
        $clone = clone $this;

        $clone->admission = $admission;

        return $clone;
    }
}
