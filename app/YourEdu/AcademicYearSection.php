<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYearSection extends Model
{
    //
    use SoftDeletes;

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function classes()
    {
        return $this->belongsToMany(ClassModel::class,'academic_section_class','academic_year_section_id','class_id')
                ->withTimestamps();
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class,'academic_year_section_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class,'academic_year_section_id');
    }
}
