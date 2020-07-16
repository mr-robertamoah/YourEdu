<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Help extends Model
{
    //
    use SoftDeletes;

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function videos()
    {
        return $this->morphToMany(Video::class,'videoable','videoables')->withTimestamps();
    }

    public function files()
    {
        return $this->morphToMany(File::class,'fileable','fileables')->withTimestamps();
    }

    public function images()
    {
        return $this->morphToMany(Image::class,'imageable','imageables')->withTimestamps();
    }
}
