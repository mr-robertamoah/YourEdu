<?php

namespace App\YourEdu;

use App\Traits\HasAliasesTrait;
use Database\Factories\SubjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory,
        HasAliasesTrait;

    protected $fillable = [
        'name', 'description', 'rationale'
    ];

    public function facilitators()
    {
        return $this->belongsToMany(Facilitator::class)->withTimestamps();
    }

    public function objectives()
    {
        return $this->morphMany(Objective::class, 'objectiveable');
    }

    public function curricula()
    {
        return $this->belongsToMany(Curriculum::class, 'curriculum_subject', 'subject_id', 'curriculum_id')
            ->using(CurriculumSubject::class)
            ->withPivot(['note', 'attachedby_type', 'attachedby_id'])
            ->withTimestamps();
    }

    public function addedby()
    {
        return $this->morphTo();
    }

    public function classes()
    {
        return $this->morphedByMany(ClassModel::class, 'subjectable', 'subjectables')
            ->withPivot(['activity'])->withTimestamps();
    }

    public function subjectClasses()
    {
        return $this->morphToMany(ClassModel::class, 'classable', 'classables', null, 'class_id')
            ->withPivot(['activity'])->withTimestamps();
    }

    public function grades()
    {
        return $this->belongsToMany(Grade::class)->withTimestamps();
    }

    public function curriculumDetails()
    {
        return $this->hasMany(CurriculumDetail::class);
    }

    public function curriculumStructures()
    {
        return $this->belongsToMany(CurriculumStructure::class, 'curriculum_structure_subject', 'subject_id', 'curriculum_structure_id')
            ->withTimestamps();
    }

    public function reportDetails()
    {
        return $this->hasMany(ReportDetail::class);
    }

    public function totalDetails()
    {
        return $this->hasMany(TotalDetail::class);
    }

    public function requests()
    {
        return $this->morphMany(Request::class, 'requestable');
    }

    public function attachments()
    {
        return $this->morphMany(PostAttachment::class, 'attachedwith');
    }

    public function discussions()
    {
        return $this->morphMany(Discussion::class, 'discussionon');
    }

    public function facilitationDetails()
    {
        return $this->morphMany(FacilitationDetail::class, 'facilitatable');
    }

    public function lessons()
    {
        return $this->morphedByMany(Lesson::class, 'lessonable', 'lessonables')
            ->withPivot(['lesson_number', 'type'])->withTimestamps();
    }

    public function lessonables()
    {
        return $this->morphMany(Lessonable::class, 'lessonable');
    }

    public function lastLesson()
    {
        return $this->lessonables()->whereNotNull('lesson_number')->last();
    }

    public function usesFacilitationDetail()
    {
        return true;
    }

    public function doesntUseFacilitationDetail()
    {
        return !$this->usesFacilitationDetail();
    }

    public function scopeSearchItems($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");
        });
    }

    public function scopeWithClasses($query, $account)
    {
        return $query
            ->with(['subjectClasses' => function ($query) use ($account) {
                $query->whereOwnedOrFacilitating($account);
            }]);
    }

    public function scopeWhereName($query, $name)
    {
        return $query->where('name', $name);
    }

    protected static function newFactory()
    {
        return SubjectFactory::new();
    }
}
