<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'name','description','rationale'
    ];

    public function addedby()
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
        return $this->morphMany(Lesson::class,'lessonable');
    }

    public function price()
    {
        return $this->morphOne(Price::class,'priceable');
    }

    public function learners()
    {
        return $this->morphedByMany(Learner::class,'coursable','coursables')
            ->withPivot(['activity','ownedby_id','ownedby_type']);
    }

    public function parents()
    {
        return $this->morphedByMany(ParentModel::class,'coursable','coursables')
            ->withPivot(['activity','ownedby_id','ownedby_type']);
    }

    public function facilitators()
    {
        return $this->morphedByMany(Facilitator::class,'coursable','coursables')
            ->withPivot(['activity','ownedby_id','ownedby_type']);
    }

    public function professionals()
    {
        return $this->morphedByMany(Professional::class,'coursable','coursables')
            ->withPivot(['activity','ownedby_id','ownedby_type']);
    }

    public function schools()
    {
        return $this->morphedByMany(School::class,'ownedby','coursables')
            ->withPivot(['activity','ownedby_id','ownedby_type']);
    }

    public function collaborations()
    {
        return $this->morphedByMany(Collaboration::class,'ownedby','coursables')
            ->withPivot(['activity','ownedby_id','ownedby_type']);
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
        return $this->belongsToMany(Grade::class)->withTimestamps();
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
    
    public function discussion()
    {
        return $this->morphOne(Discussion::class,'discussionable');
    }
    
    public function aliases()
    {
        return $this->morphMany(Alias::class,'aliasable');
    }
    
    public function discussions()
    {
        return $this->morphMany(Discussion::class,'discussionon');
    }


}
