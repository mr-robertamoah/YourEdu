<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    //
    use SoftDeletes;
    
    public function answerable()
    {
        return $this->morphTo();
    }
    
    public function answerfor()
    {
        return $this->morphTo();
    }

    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    public function question()
    {
        return $this->belongsTo(Work::class);
    }

    public function mark()
    {
        return $this->hasOne(Mark::class);
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
