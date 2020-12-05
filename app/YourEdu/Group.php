<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    //
    use SoftDeletes;

    public function paidFor()
    {
        return $this->morphMany(Payment::class,'for');
    }

    public function ownedLessons()
    {
        return $this->morphMany(Lesson::class,'ownedby');
    }

    public function extracurriculums()
    {
        return $this->morphToMany(Extracurriculum::class,'extracurriculumable','extra');
    }

    public function activityTrack()
    {
       return $this->morphOne(ActivityTrack::class,'for');
    }
    
    public function works()
    {
        return $this->morphMany(Work::class,'workable');
    }
    
    public function requestsRecieved()
    {
        return $this->morphMany(Request::class,'requestable');
    }
    
    public function requestsMade()
    {
        return $this->morphMany(Request::class,'requestfrom');
    }

    public function ownedby()
    {
        return $this->morphTo();
    }

    public function createdby()
    {
        return $this->morphTo();
    }

    public function learners()
    {
        return $this->morphedByMany(Learner::class,'groupable','groupables')
            // ->withPivot(['state','type','end_date'])
            ->withTimestamps();
    }

    public function professionals()
    {
        return $this->morphedByMany(Professional::class,'groupable','groupables')
            // ->withPivot(['state','type','end_date'])
            ->withTimestamps();
    }

    public function admins()
    {
        return $this->morphedByMany(Admin::class,'groupable','groupables')
            // ->withPivot(['state','type','end_date'])
            ->withTimestamps();
    }

    public function facilitators()
    {
        return $this->morphedByMany(Facilitator::class,'groupable','groupables')
            ->withPivot(['state','type','end_date'])
            ->withTimestamps();
    }

    public function parents()
    {
        return $this->morphedByMany(ParentModel::class,'groupable','groupables')
            // ->withPivot(['state','type','end_date'])
            ->withTimestamps();
    }
    
    public function discussions()
    {
        return $this->morphMany(Discussion::class,'discussionable');
    }

    public function questionsOwned()
    {
        return $this->morphMany(Question::class,'owned');
    }

    public function activitiesOwned()
    {
        return $this->morphMany(Activity::class,'owned');
    }
}
