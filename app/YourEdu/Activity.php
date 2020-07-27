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

    // protected $touches = [
    //     'activityby'
    // ];

    protected $casts = [
        'published' => 'datetime'
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
        return $this->morphToMany(Video::class,'videoable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function audios()
    {
        return $this->morphToMany(Audio::class,'audioable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function images()
    {
        return $this->morphToMany(Image::class,'imageable')
            ->withPivot(['state'])->withTimestamps();
    }

}
