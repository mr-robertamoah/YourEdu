<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    //
    use SoftDeletes;

    public function classes(){
        return $this->hasMany(ClassModel::class);
    }

    public function gradable(){
        return $this->morphTo();
    }

    public function subjects(){
        return $this->belongsToMany(Subject::class)->withTimestamps();
    }

    public function facilitators(){
        return $this->belongsToMany(Facilitator::class)->withTimestamps();
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }
    
    public function requests()
    {
        return $this->morphMany(Request::class,'requestable');
    }
}
