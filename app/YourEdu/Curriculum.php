<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curriculum extends Model
{
    //
    use SoftDeletes;

    public function classes(){
        return $this->hasMany(ClassModel::class);
    }

    public function curriculable(){
        return $this->morphTo();
    }

    public function structures()
    {
        return $this->hasMany(CurriculumStructure::class);
    }

    public function curriculumDetails()
    {
        return $this->hasMany(CurriculumDetail::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class,'curriculum_subject','curriculum_id','subject_id')
            ->using(CurriculumSubject::class)
            ->withPivot(['note','attachedby_type','attachedby_id'])
            ->withTimestamps();
    }

    public function schools()
    {
        return $this->belongsToMany(School::class,'curriculum_school','curriculum_id','school_id')
                ->withTimestamps();
    }

    public function grades(){
        return $this->morphMany(Grade::class,'gradable');
    }
}
