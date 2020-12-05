<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'name','description','rationale'
    ];
    
    public function aliases()
    {
        return $this->morphMany(Alias::class,'aliasable');
    }

    public function addedby()
    {
        return $this->morphTo();
    }

    public function attachments()
    {
        return $this->morphMany(PostAttachment::class,'attachedwith');
    }
    
    public function discussions()
    {
        return $this->morphMany(Discussion::class,'discussionon');
    }

    public function learners()
    {
        return $this->morphedByMany(Learner::class,'programmable','programmables');
    }

    public function schools()
    {
        return $this->morphedByMany(School::class,'programmable','programmables');
    }

    public function facilitators()
    {
        return $this->morphedByMany(facilitator::class,'programmable','programmables');
    }

    public function professionals()
    {
        return $this->morphedByMany(Professional::class,'programmable','programmables');
    }
}
