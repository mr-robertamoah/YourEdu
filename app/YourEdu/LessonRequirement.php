<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonRequirement extends Model
{
    //
    use SoftDeletes;

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function links()
    {
        return $this->morphMany(Link::class,'linkable');
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
}
