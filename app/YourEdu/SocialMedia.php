<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialMedia extends Model
{
    //
    use SoftDeletes;
    

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
