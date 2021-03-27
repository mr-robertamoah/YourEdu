<?php

namespace App\YourEdu;

use App\Traits\AssessmentTrait;
use App\Traits\DashboardItemTrait;
use Database\Factories\ExtracurriculumFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Extracurriculum extends Model
{
    
    use SoftDeletes, DashboardItemTrait, AssessmentTrait, HasFactory;

    protected $fillable = [
        'name', 'description', 'state'
    ];

    public function likes(){
        return $this->morphMany(Like::class,'likeable');
    }

    public function prices()
    {
        return $this->morphMany(Price::class,'priceable');
    }

    public function subscriptions()
    {
        return $this->morphMany(Subscription::class,'subscribable');
    }

    public function grades(){
        return $this->morphToMany(Grade::class,'gradeable','gradeables')
            ->withTimestamps();
    }

    public function courses()
    {
        return $this->morphToMany(Course::class,'coursable','coursables')
            ->withPivot(['activity'])->withTimestamps();
    }

    public function programs()
    {
        return $this->morphToMany(Program::class,'programmable','programmables')
            ->withTimestamps();
    }

    public function addedby()
    {
        return $this->morphTo();
    }

    public function ownedby()
    {
        return $this->morphTo();
    }

    public function extrable()
    {
        return $this->morphTo();
    }

    public function schools()
    {
        return $this->morphedByMany(School::class,'extracurriculumable','extra')
            ->withTimestamps();
    }

    public function facilitators(){
        return $this->morphedByMany(Facilitator::class,'extracurriculumable','extra')
            ->withPivot(['activity'])
            ->withTimestamps();
    }

    public function professionals(){
        return $this->morphedByMany(Professional::class,'extracurriculumable','extra')
            ->withPivot(['activity'])
            ->withTimestamps();
    }

    public function learners(){
        return $this->morphedByMany(Learner::class,'extracurriculumable','extra')
            ->withTimestamps();
    }

    public function classes()
    {
        return $this->morphToMany(ClassModel::class,'classable','classables',null,'class_id')
            ->withTimestamps();
    }

    public function groups()
    {
        return $this->morphedByMany(Group::class,'extracurriculumable','extra');
    }

    public function lessons()
    {
        return $this->morphToMany(Lesson::class,'lessonable','lessonables')
            ->withPivot(['type','lesson_number'])->withTimestamps();
    }
    
    public function requests()
    {
        return $this->morphMany(Request::class,'requestable');
    }

    public function topics()
    {
        return $this->morphMany(Topic::class,'topicable');
    }

    public function flags()
    {
        return $this->morphMany(Flag::class,'flaggable');
    }

    public function permissions()
    {
        return $this->morphMany(Permission::class,'permissible');
    }
    
    public function discussions()
    {
        return $this->morphMany(Discussion::class,'discussionfor');
    }

    public function discussion()
    {
        return $this->discussions->first();
    }
    
    public function payments()
    {
        return $this->morphMany(Payment::class,'what');
    }

    public function parents()
    {
        return $this->morphedByMany(ParentModel::class,'extracurriculumable','extra')
            ->withPivot(['activity'])->withTimestamps();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function assessments()
    {
        return $this->morphByMany(Assessment::class,'assessmentable');
    }

    public function hasDiscussion()
    {
        return $this->discussions->count() > 0;
    }

    public function doesntHaveDiscussion()
    {
        return !$this->hasDiscussion();
    }

    public function items()
    {
        return $this->classes->merge(
            $this->programs()->hasOwner()->get()
        );
    }

    public function hasPayments()
    {
        return $this->whereHas('payments')
            ->orWhereHas('programs',function($query) {
                $query->whereHas('payments');
            })
            ->orWhereHas('classes',function($query) {
                $query->whereHas('payments');
            })
            ->count() > 0;
    }

    public function isUsedByAnotherItem()
    {
        return $this->programs->whereNotNull('ownedby_type')->count() ||
            $this->classes->count();
    }

    protected static function newFactory()
    {
        return ExtracurriculumFactory::new();
    }
}
