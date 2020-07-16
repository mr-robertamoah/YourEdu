<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fee extends Model
{
    //
    use SoftDeletes;

    public function paymentFor()
    {
        return $this->morphMany(Payment::class,'paidfor');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class,'class_id');
    }

    public function academicYearSection()
    {
        return $this->belongsTo(AcademicYearSection::class,'academic_term_id');
    }
}
