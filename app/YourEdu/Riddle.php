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

    // protected $touches = [
    //     'posts'
    // ];

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

    public function images()
    {
        return $this->morphToMany(Image::class,'imageable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function videos()
    {
        return $this->morphToMany(Video::class,'videoable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function audios()
    {
        return $this->morphToMany(Audio::class,'audioable')
        ->withPivot(['state'])->withTimestamps();
    }
}
