<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
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

    public function positionable()
    {
        return $this->morphTo();
    }
}
