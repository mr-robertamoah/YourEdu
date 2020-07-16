<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    //
    use SoftDeletes;

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function possibleAnswers()
    {
        return $this->morphMany(PossibleAnswer::class,'question');
    }

    public function files()
    {
        return $this->morphToMany(File::class,'fileable','fileables');
    }

    public function audios()
    {
        return $this->morphToMany(Audio::class,'audioable','audioables');
    }

    public function videos()
    {
        return $this->morphToMany(Video::class,'videoable','videoables');
    }

    public function images()
    {
        return $this->morphToMany(Image::class,'imageable','imageables');
    }

    public function posts()
    {
        return $this->morphMany(Post::class,'postable');
    }
}
