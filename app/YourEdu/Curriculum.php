<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curriculum extends Model
{
    //
    use SoftDeletes;

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

    public function schools()
    {
        return $this->morphedByMany(School::class,'curriculumable','curriculumables');
    }

    public function classes()
    {
        return $this->morphedByMany(ClassModel::class,'curriculumable','curriculumables');
    }

    public function learners()
    {
        return $this->morphedByMany(Learner::class,'curriculumable','curriculumables');
    }

    public function facilitators()
    {
        return $this->morphedByMany(Facilitator::class,'curriculumable','curriculumables');
    }

    public function programs()
    {
        return $this->morphedByMany(Program::class,'curriculumable','curriculumables');
    }

    public function grades(){
        return $this->morphMany(Grade::class,'gradable');
    }
}
