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

    public function price()
    {
        return $this->morphOne(Price::class,'priceable');
    }

    public function objectives()
    {
        return $this->morphMany(Objective::class,'objectiveable');
    }

    public function summary()
    {
        return $this->morphOne(Summary::class,'summariable');
    }

    public function collaboration()
    {
        return $this->morphOne(Collaboration::class,'collaborationable');
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

    public function professionals()
    {
        return $this->belongsToMany(Professional::class)->withTimestamps();
    }

    public function facilitators()
    {
        return $this->belongsToMany(Facilitator::class)->withTimestamps();
    }
    
    public function discussions()
    {
        return $this->morphMany(Discussion::class,'discussionon');
    }


}
