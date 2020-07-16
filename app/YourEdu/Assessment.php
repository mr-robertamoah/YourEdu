<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
{
    //
    use SoftDeletes;

    public function works()
    {
        return $this->hasMany(Work::class);
    }

    public function assessmentSections()
    {
        return $this->hasMany(AssessmentSection::class);
    }

    public function academicYearSection()
    {
        return $this->belongsTo(AcademicYearSection::class,'academic_year_section_id');
    }

    public function assessmentable()
    {
        return $this->morphTo();
    }

    public function assessmentby()
    {
        return $this->morphTo();
    }

    public function reportDetail()
    {
        return $this->belongsTo(ReportDetail::class);
    }
}
