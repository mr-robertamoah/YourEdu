<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admission extends Model
{
    //
    use SoftDeletes;

    public function price()
    {
        return $this->morphOne(Price::class,'priceable');
    }

    public function grade()
    {
        return $this->belongsTo(Price::class);
    }

    public function learner()
    {
        return $this->belongsTo(Learner::class);
    }

    public function admmissionfrom()
    {
        return $this->morphTo();
    }

    public function admmissionto()
    {
        return $this->morphTo();
    }

    public function assessments()
    {
        return $this->morphMany(Assessment::class,'assessmentable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }
    
}
