<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Riddle extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'author', 'riddle', 'published'
    ];

    protected $casts = [
        'published' => 'datetime',
    ];

    public function addedby()
    {
        return $this->morphTo();
    }

    public function riddleable()
    {
        return $this->morphTo();
    }

    public function authoredby()
    {
        return $this->morphTo();
    }
    
    public function answers()
    {
        return $this->morphMany(Answer::class,'answerfor');
    }
}
