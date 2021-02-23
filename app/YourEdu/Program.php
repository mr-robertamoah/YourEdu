<?php

namespace App\YourEdu;

use App\Traits\AssessmentTrait;
use App\Traits\NotOwnedbyTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    //
    use SoftDeletes, NotOwnedbyTrait, AssessmentTrait;

    protected $fillable = [
        'name','description','rationale', 'state'
    ];
    
    public function aliases()
    {
        return $this->morphMany(Alias::class,'aliasable');
    }

    public function addedby()
    {
        return $this->morphTo();
    }

    public function ownedby()
    {
        return $this->morphTo();
    }
    
    public function payments()
    {
        return $this->morphMany(Payment::class,'what');
    }

    public function attachments()
    {
        return $this->morphMany(PostAttachment::class,'attachedwith');
    }
    
    public function discussions()
    {
        return $this->morphMany(Discussion::class,'discussionfor');
    }

    public function learners()
    {
        return $this->morphedByMany(Learner::class,'programmable','programmables')
            ->withTimestamps();
    }

    public function schools()
    {
        return $this->morphedByMany(School::class,'programmable','programmables')
            ->withTimestamps();
    }

    public function facilitators()
    {
        return $this->morphedByMany(facilitator::class,'programmable','programmables')
            ->withTimestamps();
    }

    public function professionals()
    {
        return $this->morphedByMany(Professional::class,'programmable','programmables')
            ->withTimestamps();
    }

    public function extracurriculums()
    {
        return $this->morphedByMany(Extracurriculum::class,'programmable','programmables')
            ->withTimestamps();
    }

    public function lessons()
    {
        return $this->morphedByMany(Lesson::class,'programmable','programmables')
            ->withTimestamps();
    }

    public function courses()
    {
        return $this->morphedByMany(Course::class,'programmable','programmables')
            ->withTimestamps();
    }

    public function coursesService()
    {
        return $this->morphToMany(Course::class,'coursable','coursables')
            ->withTimestamps();
    }

    public function programs()
    {
        return $this->morphToMany(Program::class,'programmable','programmables')
                ->withTimestamps();
    }

    public function grades(){
        return $this->morphToMany(Grade::class,'gradeable','gradeables');
    }

    public function prices()
    {
        return $this->morphMany(Price::class,'priceable');
    }

    public function subscriptions()
    {
        return $this->morphMany(Subscription::class,'subscribable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }
}
