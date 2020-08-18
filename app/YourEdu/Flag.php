<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Flag extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'admin_id', 'status', 'reason'
    ];

    public function flaggedby()
    {
        return $this->morphTo();
    }

    public function flaggable()
    {
        return $this->morphTo();
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function bans()
    {
        return $this->morphOne(Ban::class,'issuedfor');
    }
}
