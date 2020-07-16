<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentSection extends Model
{
    //
    use SoftDeletes;

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}
