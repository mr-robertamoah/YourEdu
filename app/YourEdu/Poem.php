<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Poem extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'title', 'author', 'about', 'published'
    ];

    // protected $touches = [
    //     'posts'
    // ];

    protected $casts = [
        'published' => 'datetime'
    ];

    public function addedby()
    {
        return $this->morphTo();
    }

    public function authoredby()
    {
        return $this->morphTo();
    }

    public function poemable()
    {
        return $this->morphTo();
    }

    public function poemSections()
    {
        return $this->hasMany(PoemSection::class);
    }

    public function images()
    {
        return $this->morphToMany(Image::class,'imageable')
        ->withPivot(['state'])->withTimestamps();
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
}
