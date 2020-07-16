<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportSection extends Model
{
    //
    use SoftDeletes;

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class,'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
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
