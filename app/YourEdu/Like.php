<?php

namespace App\YourEdu;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    //

    protected $fillable = [
        'user_id', 'level'
    ];

    protected $touches = [
        'likeable'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likedby()
    {
        return $this->morphTo();
    }

    public function likeable()
    {
        return $this->morphTo();
    }
}
