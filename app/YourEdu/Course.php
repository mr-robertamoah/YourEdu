<?php

namespace App\YourEdu;

use App\Traits\AssessmentTrait;
use App\Traits\DashboardItemTrait;
use App\Traits\NotOwnedbyTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property bool $stand_alone
 */
class Course extends Model
{
    //
    use SoftDeletes, NotOwnedbyTrait, DashboardItemTrait, AssessmentTrait;

    protected $fillable = [
        'name','description', 'state', 'stand_alone'
    ];

    protected $casts = [
        'stand_alone' => 'boolean'
    ];

    public function addedby()
    {
        return $this->morphTo();
    }

    public function ownedby()
    {
        return $this->morphTo();
    }

    public function attachments()
    {
        return $this->morphMany(PostAttachment::class,'attachedwith');
    }

    public function likes(){
        return $this->morphMany(Like::class,'likeable');
    }

    public function lessons()
    {
        return $this->morphedByMany(Lesson::class,'coursable','coursables')
            ->withPivot(['activity'])->withTimestamps();
    }

    public function subscriptions()
    {
        return $this->morphMany(Subscription::class,'subscribable');
    }

    public function classes(){
        return $this->morphToMany(ClassModel::class,'classable','classables',null,'class_id');
    }

    public function courseSections()
    {
        return $this->hasMany(CourseSection::class);
    }

    public function prices()
    {
        return $this->morphMany(Price::class,'priceable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function learners()
    {
        return $this->morphedByMany(Learner::class,'coursable','coursables')
            ->withPivot(['activity','resource'])->withTimestamps();
    }

    public function parents()
    {
        return $this->morphedByMany(ParentModel::class,'coursable','coursables')
            ->withPivot(['activity'])->withTimestamps();
    }

    public function facilitators()
    {
        return $this->morphedByMany(Facilitator::class,'coursable','coursables')
            ->withPivot(['activity','resource'])->withTimestamps();
    }

    public function professionals()
    {
        return $this->morphedByMany(Professional::class,'coursable','coursables')
            ->withPivot(['activity','resource'])->withTimestamps();
    }

    public function schools()
    {
        return $this->morphedByMany(School::class,'coursable','coursables')
            ->withPivot(['activity','resource'])->withTimestamps();
    }

    public function extracurriculums()
    {
        return $this->morphedByMany(Extracurriculum::class,'coursable','coursables')
            ->withTimestamps();
    }

    public function collaborations()
    {
        return $this->morphedByMany(Collaboration::class,'coursable','coursables')
            ->withPivot(['activity','resource'])->withTimestamps();
    }

    public function objectives()
    {
        return $this->morphMany(Objective::class,'objectiveable');
    }

    public function summary()
    {
        return $this->morphOne(Summary::class,'summariable');
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
            ->withPivot(['activity'])->withTimestamps();
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
    
    public function aliases()
    {
        return $this->morphMany(Alias::class,'aliasable');
    }
    
    public function discussions()
    {
        return $this->morphMany(Discussion::class,'discussionfor');
    }
    
    public function payments()
    {
        return $this->morphMany(Payment::class,'what');
    }


}
