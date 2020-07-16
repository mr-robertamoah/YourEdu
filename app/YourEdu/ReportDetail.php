<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportDetail extends Model
{
    //
    use SoftDeletes;

    public function learner()
    {
        return $this->belongsTo(Learner::class);
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function reportSection()
    {
        return $this->belongsTo(ReportSection::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function totalDetail()
    {
        return $this->belongsTo(TotalDetail::class);
    }

    public function gradingDetail()
    {
        return $this->belongsTo(GradingDetail::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    public function works()
    {
        return $this->hasMany(Work::class);
    }

    public function positions()
    {
        return $this->morphMany(Position::class,'positionable');
    }
}
