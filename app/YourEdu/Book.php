<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'title', 'author', 'about', 'published'
    ];

    // protected $touches = [
    //     'bookable'
    // ];

    protected $casts = [
        'published' => 'datetime',
    ];

    public function authoredby()
    {
        return $this->morphTo();
    }

    public function bookable()
    {
        return $this->morphTo();
    }

    public function addedby()
    {
        return $this->morphTo();
    }

    public function price()
    {
        return $this->morphOne(Price::class,'priceable');
    }

    public function reads()
    {
        return $this->hasMany(Read::class);
    }

    public function files()
    {
        return $this->morphToMany(File::class,'fileable');
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

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

}
