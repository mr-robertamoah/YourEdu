<?php

namespace App\Traits;

use App\YourEdu\ClassModel;
use App\YourEdu\Course;
use App\YourEdu\CourseSection;
use App\YourEdu\Extracurriculum;
use App\YourEdu\Lesson;
use App\YourEdu\Subject;

trait FacilitatingAccountsTrait
{
    

    public function ownedOrFacilitatingCourses()
    {
        return $this->whereOwnedOrFacilitatingCourses()
            ->get();
    }

    public function scopeWhereOwnedOrFacilitatingCourses($query)
    {
        return Course::whereOwnedOrFacilitating($this);
    }

    public function ownedOrFacilitatingClasses()
    {
        return $this->whereOwnedOrFacilitatingClasses()
            ->get();
    }

    public function scopeWhereOwnedOrFacilitatingClasses($query)
    {
        return ClassModel::whereOwnedOrFacilitating($this);
    }

    public function ownedOrFacilitatingLessons()
    {
        return $this->whereOwnedOrFacilitatingLessons()
            ->get();
    }

    public function scopeWhereOwnedOrFacilitatingLessons($query)
    {
        return Lesson::where(function($query) {
            $query->whereOwnedby($this);
        })->orWhere(function($query) {
            $query->whereAddedOrCollaborator($this);
        });
    }

    public function ownedOrFacilitatingExtracurriculums()
    {
        return $this->whereOwnedOrFacilitatingExtracurriculums()
            ->get();
    }

    public function scopeWhereOwnedOrFacilitatingExtracurriculums($query)
    {
        return Extracurriculum::where(function($query) {
                $query->whereOwnedby($this);
            })->orWhere(function($query) {
                $query->whereHas("{$this->accountType}s",function($query) {
                    $query->where('user_id', $this->user_id);
                });
            });
    }

    public function ownedCourseSections()
    {
        return $this->whereOwnedCourseSections()->get();
    }

    public function scopeWhereOwnedCourseSections($query)
    {
        return CourseSection::whereHas('course',function($query) {
                $query->whereOwnedby($this);
            })
            ->with('course:id,name');
    }

    public function ownedOrFacilitatingCourseSections()
    {
        return $this->whereOwnedOrFacilitatingCourseSections()->get();
    }

    public function scopeWhereOwnedOrFacilitatingCourseSections($query)
    {
        return CourseSection::whereHas('course', function($query) {
                $query->whereOwnedOrFacilitating($this);
            })
            ->with('course:id,name');
    }

    public function ownedClassSubjects()
    {
        return $this->whereOwnedClassSubjects()->get();
    }

    public function scopeWhereOwnedClassSubjects($query)
    {
        return Subject::whereHas('subjectClasses', function($query) {
                $query->whereOwnedby($this);
            })
            ->with('subjectClasses');
    }

    public function ownedOrFacilitatingClassSubjects()
    {
        return $this->whereOwnedOrFacilitatingClassSubjects()->get();
    }

    public function scopeWhereOwnedOrFacilitatingClassSubjects($query)
    {
        return Subject::whereHas('subjectClasses', function($query) {
                $query->whereOwnedOrFacilitating($this);
            })
            ->with(['subjectClasses' => function($query) {
                $query->whereOwnedOrFacilitating($this);
            }]);
    }
}
