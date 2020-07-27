<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'question', 'state','published'
    ];

    // protected $touches = [
    //     'posts'
    // ];

    public function questionedby(){
        return $this->morphTo();
    }

    public function questionable(){
        return $this->morphTo();
    }
    
    public function answers()
    {
        return $this->morphMany(Answer::class,'answerfor');
    }

    public function possibleAnswers()
    {
        return $this->morphMany(PossibleAnswer::class,'question');
    }

    public function files()
    {
        return $this->morphToMany(File::class,'fileable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function audios()
    {
        return $this->morphToMany(Audio::class,'audioable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function videos()
    {
        return $this->morphToMany(Video::class,'videoable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function images()
    {
        return $this->morphToMany(Image::class,'imageable')
        ->withPivot(['state'])->withTimestamps();
    }

    // public function posts()
    // {
    //     return $this->morphMany(Post::class,'postable');
    // }
}
