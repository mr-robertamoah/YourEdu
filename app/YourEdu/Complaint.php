<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    //
    use SoftDeletes;

    public function bans()
    {
        return $this->morphOne(Ban::class,'issuedfor');
    }
}
