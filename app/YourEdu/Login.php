<?php

namespace App\YourEdu;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    //

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
