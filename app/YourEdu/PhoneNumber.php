<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhoneNumber extends Model
{
    //
    use SoftDeletes;

    public function phoneable()
    {
        return $this->morphTo();
    }
}
