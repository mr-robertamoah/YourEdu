<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    //
    use SoftDeletes;

    public function objectives()
    {
        return $this->morphMany(Objective::class,'objectiveable');
    }

    public function summary()
    {
        return $this->morphOne(Summary::class,'summariable');
    }

    public function assessments()
    {
        return $this->morphMany(Assessment::class,'assessmentable');
    }

    public function topicable()
    {
        return $this->morphTo();
    }
    
    public function discussion()
    {
        return $this->morphOne(Discussion::class,'discussionon');
    }

}
