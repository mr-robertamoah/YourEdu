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
        'published_at','due_at', 'type', 'restricted'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'restricted' => 'bool',
        'due_at' => 'datetime',
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

    public function courseSections()
    {
        return $this->morphedByMany(
            related: CourseSection::class,
            name: 'assessmentable',
            table: 'assessmentables'
        )->withTimestamps();
    }

    public function subjects()
    {
        return $this->morphedByMany(
            related: Subject::class,
            name: 'assessmentable',
            table: 'assessmentables'
        )->withTimestamps();
    }
    
    public function payments()
    {
        return $this->morphMany(Payment::class,'what');
    }

    public function assessmentable()
    {
        return $this->morphTo();
    }

    public function assessmentables()
    {
        return $this->hasMany(Assessmentable::class);
    }

    public function addedby()
    {
        return $this->morphTo();
    }

    public function reportDetail()
    {
        return $this->belongsTo(ReportDetail::class);
    }
    
    public function discussions()
    {
        return $this->morphMany(Discussion::class,'discussionfor');
    }

    public function discussion()
    {
        return $this->discussions->first();
    }

    public function hasDiscussion()
    {
        return $this->discussions->count() > 0;
    }

    public function doesntHaveDiscussion()
    {
        return !$this->hasDiscussion();
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

    public function items()
    {
        return Assessmentable::where('assessment_id', $this->id)
            ->has('assessmentable')->get()
            ->pluck('assessmentable');
    }

    public function doesntHaveSpecificAssessmentable($assessmentable, $itemable)
    {
        return is_null(
            $this->specificAssesmentable($assessmentable, $itemable)
        );
    }

    public function specificAssesmentable($assessmentable, $itemable)
    {
        return $this->assessmentables()
            ->where('assessmentable_type', $assessmentable::class)
            ->where('assessmentable_id', $assessmentable->id)
            ->where('itemable_type', $itemable::class)
            ->where('itemable_id', $itemable->id)
            ->first();
    }

    public function hasPayments()
    {
        return $this->has('payments')
            ->orWhereHas('programs',function($query) {
                $query->has('payments');
            })
            ->orWhereHas('classes',function($query) {
                $query->has('payments');
            })
            ->orWhereHas('lessons',function($query) {
                $query->has('payments');
            })
            ->orWhereHas('courses',function($query) {
                $query->has('payments');
            })
            ->orWhereHas('extracurriculums',function($query) {
                $query->has('payments');
            })
            ->count() > 0;
    }

    public function isUsedByAnotherItem()
    {
        return $this->programs->count() ||
            $this->extracurriculums->count() ||
            $this->lessons->count() ||
            $this->courses->count() ||
            $this->courseSections->count() ||
            $this->subjects->count() ||
            $this->classes->count();
    }

    public function doesntHaveAssessmentSections()
    {
        return $this->assessmentSections->count() < 1;
    }

    public function notRemovingAllSections(array $sections)
    {
        $assessmentSectionIds = $this->assessmentSections->pluck('id')->toArray();

        return count(
            array_filter($sections, function($section) use ($assessmentSectionIds) {
                return in_array(
                    $section->assessmentSectionId,
                    $assessmentSectionIds
                );
            })
        ) < $this->assessmentSections->count();
    }

    protected static function newFactory()
    {
        return AssessmentFactory::new();
    }
}
