<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admission extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'learner_id', 'grade_id', 'school_id', 'state', 'type'
    ];

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

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function admmissionfrom()
    {
        return $this->morphTo();
    }

    public function admmissionto()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function assessments()
    {
        return $this->morphByMany(Assessment::class,'assessmentable');
    }
    
}
