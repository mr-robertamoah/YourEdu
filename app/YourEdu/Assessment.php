<?php

namespace App\YourEdu;

use Database\Factories\AssessmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
{
    //
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name', 'description', 'total_mark','duration',
        'publish_date','due_date'
    ];

    protected $casts = [
        'publish_date' => 'datetime',
        'due_date' => 'datetime',
    ];

    public function works()
    {
        return $this->hasMany(Work::class);
    }

    public function hasBeenTaken()
    {
        return $this->works->count() > 0;
    }

    public function assessmentSections()
    {
        return $this->hasMany(AssessmentSection::class);
    }

    public function academicYearSections()
    {
        return $this->morphedByMany(
            related: AcademicYearSection::class,
            name: 'assessmentable',
            table: 'assessmentables'
        )->withTimestamps();
    }

    public function lessons()
    {
        return $this->morphedByMany(
            related: Lesson::class,
            name: 'assessmentable',
            table: 'assessmentables'
        )->withTimestamps();
    }

    public function programs()
    {
        return $this->morphedByMany(
            related: Program::class,
            name: 'assessmentable',
            table: 'assessmentables'
        )->withTimestamps();
    }

    public function courses()
    {
        return $this->morphedByMany(
            related: Course::class,
            name: 'assessmentable',
            table: 'assessmentables'
        )->withTimestamps();
    }

    public function classes()
    {
        return $this->morphedByMany(
            related: ClassModel::class,
            name: 'assessmentable',
            table: 'assessmentables'
        )->withTimestamps();
    }

    public function extracurriculums()
    {
        return $this->morphedByMany(
            related: Extracurriculum::class,
            name: 'assessmentable',
            table: 'assessmentables'
        )->withTimestamps();
    }

    public function allItems()
    {
        return $this->extracurriculums->merge(
            $this->classes->merge(
                $this->courses->merge(
                    $this->programs->merge(
                        $this->lessons
                    )
                )
            )
        );
    }

    public function assessmentable()
    {
        return $this->morphTo();
    }

    public function addedby()
    {
        return $this->morphTo();
    }

    public function reportDetail()
    {
        return $this->belongsTo(ReportDetail::class);
    }

    public function doesntHaveAssessmentSections()
    {
        return $this->assessmentSections->count() < 1;
    }

    protected static function newFactory()
    {
        return AssessmentFactory::new();
    }
}
