<?php

namespace App\YourEdu;

use App\Traits\FlaggableTrait;
use App\Traits\HasCommentsTrait;
use App\Traits\HasParticipantsTrait;
use App\Traits\HasSocialMediaTrait;
use Database\Factories\AssessmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
{
    use SoftDeletes, 
        HasFactory,
        FlaggableTrait,
        HasSocialMediaTrait,
        HasParticipantsTrait,
        HasCommentsTrait;

    const MARKERS_ACCOUNT_TYPES = ['professional', 'facilitator'];

    protected $fillable = [
        'name', 'description', 'total_mark','duration',
        'published_at','due_at', 'type', 'social'
    ];

    protected $casts = [
        'published_at' => 'datetime',
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

    public function attachments()
    {
        return $this->morphMany(PostAttachment::class,'attachable');
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
        return Assessmentable::query()
            ->where('assessment_id', $this->id)
            ->whereNotMarker()
            ->get()
            ->pluck('assessmentable');
    }

    public function markers()
    {
        return Assessmentable::query()
            ->where('assessment_id', $this->id)
            ->whereMarker()
            ->get()
            ->pluck('assessmentable');
    }

    public function doesntHaveSpecificAssessmentable($assessmentable, $itemable)
    {
        return is_null(
            $this->specificAssessmentable($assessmentable, $itemable)
        );
    }

    public function specificAssessmentable($assessmentable, $itemable)
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

    public function isntUsedByAnotherItem()
    {
        return !$this->isUsedByAnotherItem();
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

    public function isSocial()
    {
        return $this->social;
    }

    public function isNotSocial()
    {
        return ! $this->isSocial();
    }

    public function isMarker($userId)
    {
        return $this->assessmentables()
            ->whereMarker()
            ->whereAssessmentableUser($userId)
            ->exists();
    }

    public function isNotMarker($userId)
    {
        return ! $this->isMarker($userId);
    }

    public function scopeWherePublished($query)
    {
        return $query->where(function($query) {
            $query
                ->whereNull('published_at')
                ->orWhereDate('published_at', '<=' , now());
        });
    }

    public function scopeSearchItems($query, $search)
    {
        return $query->where(function($q) use ($search){
            $q->where('name','like',"%$search%")
                ->orWhere('description','like',"%$search%");
        });
    }

    public function scopeWhereNotOwnedbyBasedOnUserId($query, $userId)
    {
        return $query->where(function($query) use ($userId) {
            $query->whereHasMorph('addedby', '*', function($query, $type) use ($userId) {
                $query->whereNotUser($userId);
            });
        });
    }

    public function scopeWithRelations($query)
    {
        return $query->with([
            'addedby', 'attachments', 
            'assessmentSections' => function($query) {
                $query->with([
                    'questions' => function($query) {
                        $query->with([
                            'images', 'videos', 'audios', 'files', 'possibleAnswers'
                        ]);
                    }
                ]);
            }
        ]);
    }

    public function scopeWhereNotSocial($query)
    {
        return $query->wherehas('assessmentables');
    }

    public function scopeWhereSocial($query)
    {
        return $query->whereDoesntHave('assessmentables');
    }

    protected static function newFactory()
    {
        return AssessmentFactory::new();
    }
}
