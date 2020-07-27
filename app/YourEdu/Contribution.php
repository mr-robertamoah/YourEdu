<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contribution extends Model
{
    //
    use SoftDeletes;

    public function markedby()
    {
        return $this->morphTo();
    }

    public function mark()
    {
        return $this->morphOne(Mark::class,'markable');
    }

    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    public function flags()
    {
        return $this->morphMany(Flag::class,'flaggable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
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

}

