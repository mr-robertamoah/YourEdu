<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ban extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'type', 'state', 'due_date'
    ];

    protected $casts = [
        'due_date' => 'datetime'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function bannable()
    {
        return $this->morphTo();
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
