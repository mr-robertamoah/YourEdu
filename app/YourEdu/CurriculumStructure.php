<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurriculumStructure extends Model
{
    //
    use SoftDeletes;

    public function precedingStructure()
    {
        return $this->belongsTo(CurriculumStructure::class,'structure_id');
    }

    public function succeedingStructure()
    {
        return $this->hasOne(CurriculumStructure::class,'structure_id');
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function curriculumDetails()
    {
        return $this->hasMany(CurriculumDetail::class,'curriculum_structure_id');
    }
}
