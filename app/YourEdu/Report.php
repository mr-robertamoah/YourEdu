<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    //
    use SoftDeletes;

    public function learner()
    {
        return $this->belongsTo(Learner::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class,'class_id');
    }

    public function school()
    {
        return $this->belongsTo(Learner::class);
    }

    public function academicYearSection()
    {
        return $this->belongsTo(AcademicYearSection::class,'academic_year_section_id');
    }

    public function reportSections()
    {
        return $this->hasMany(ReportSection::class);
    }

    public function reportDetails()
    {
        return $this->hasMany(ReportDetail::class);
    }

    public function totalDetail()
    {
        return $this->hasOne(TotalDetail::class);
    }
}
