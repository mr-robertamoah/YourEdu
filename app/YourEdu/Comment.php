<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //

    public function likes(){
        return $this->morphMany(Like::class,'likeable');
    }

    public function commentedby(){
        return $this->morphTo();
    }

    public function commentable(){
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function files()
    {
        return $this->morphToMany(File::class,'fileable','fileables')->withTimestamps();
    }

    public function audios()
    {
        return $this->morphToMany(Audio::class,'audioable','audioables')->withTimestamps();
    }

    public function videos()
    {
        return $this->morphToMany(Video::class,'videoable','videoables')->withTimestamps();
    }

    public function images()
    {
        return $this->morphToMany(Image::class,'imageable','imageables')->withTimestamps();
    }
}
