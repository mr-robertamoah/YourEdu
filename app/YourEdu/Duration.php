<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Duration extends Model
{
    //
    use SoftDeletes;

    public function durationable()
    {
        return $this->morphTo();
    }
}
