<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TotalDetail extends Model
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

    public function gradingDetail()
    {
        return $this->belongsTo(GradingDetail::class);
    }

    public function reportDetails()
    {
        return $this->hasMany(ReportDetail::class);
    }

    public function positions()
    {
        return $this->morphMany(Position::class,'positionable');
    }
}
