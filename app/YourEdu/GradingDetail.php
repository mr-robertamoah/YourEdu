<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradingDetail extends Model
{
    //
    use SoftDeletes;

    public function gradingSystem()
    {
        return $this->belongsTo(GradingSystem::class,'grading_system_id');
    }
}
