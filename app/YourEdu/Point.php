<?php

namespace App\YourEdu;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    // issue points to some account on the performance of social duties

    protected $touches = ['pointable'];

    protected $fillable = ['value','user_id'];

    public function pointable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
