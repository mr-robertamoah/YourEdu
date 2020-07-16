<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ban extends Model
{
    //
    use SoftDeletes;

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function issuedfor()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }
}
