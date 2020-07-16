<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYear extends Model
{
    //
    use SoftDeletes;

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function academicYearSections()
    {
        return $this->hasMany(AcademicYearSection::class);
    }
}
