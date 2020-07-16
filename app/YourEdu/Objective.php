<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Objective extends Model
{
    //
    use SoftDeletes;

    public function objectiveable()
    {
        return $this->morphTo();
    }

    public function objectiveby()
    {
        return $this->morphTo();
    }
}
