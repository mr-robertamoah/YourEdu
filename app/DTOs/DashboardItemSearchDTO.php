<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class DashboardItemSearchDTO
{
    public ?string $search = null;
    public ?Model $searcher = null;
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $for = null;
    public bool $searchCourses = false;
    public bool $searchCourseSections = false;
    public bool $searchClasses = false;
    public bool $searchExtracurriculums = false;
    public bool $searchAcademicYears = false;
    public bool $searchSubjects = false;
    public bool $searchPrograms = false;
    public bool $searchLessons = false;
    public array $items = [];

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->for = $request->for;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->search = $request->search;
        $self->items = $request->items ? $request->items : [];

        if (in_array('all', $self->items)) {
            $self->searchCourses = true;
            $self->searchClasses = true;
            $self->searchExtracurriculums = true;
            $self->searchPrograms = true;
            $self->searchLessons = true;
            $self->searchSubjects = true;
            $self->searchCourseSections = true;

            return $self;
        }

        if (in_array('courses', $self->items)) {
            $self->searchCourses = true;
        }

        if (in_array('course sections', $self->items)) {
            $self->searchCourseSections = true;
        }

        if (in_array('classes', $self->items)) {
            $self->searchClasses = true;
        }

        if (in_array('extracurriculums', $self->items)) {
            $self->searchExtracurriculums = true;
        }

        if (in_array('subjects', $self->items)) {
            $self->searchSubjects = true;
        }

        if (in_array('programs', $self->items)) {
            $self->searchPrograms = true;
        }

        if (in_array('lessons', $self->items)) {
            $self->searchLessons = true;
        }

        if (in_array('academicYears', $self->items)) {
            $self->searchAcademicYears = true;
        }

        return $self;
    }

    public function withSearcher(Model $searcher)
    {
        $clone = clone $this;

        $clone->searcher = $searcher;

        return $clone;
    }
}
