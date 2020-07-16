<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'description', 
    ];

    public function activityby()
    {
        return $this->morphTo();
    }

    public function activityfor()
    {
        return $this->morphTo();
    }

    public function videos()
    {
        return $this->morphToMany(Video::class,'videoable','videoables');
    }

    public function audios()
    {
        return $this->morphToMany(Audio::class,'audioable','audioables');
    }

    public function images()
    {
        return $this->morphToMany(Image::class,'imageable','imageables');
    }

}
