<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradingSystem extends Model
{
    //
    use SoftDeletes;

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function gradingDetails()
    {
        return $this->hasMany(GradingDetail::class,'grading_system_id');
    }
}
