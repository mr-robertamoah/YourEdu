<?php

namespace App\YourEdu;

use Database\Factories\RiddleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Riddle extends Model
{
    //
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'author_names', 'body', 'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // protected $touches = [
    //     'posts'
    // ];

    public function addedby()
    {
        return $this->morphTo();
    }

    public function riddleable()
    {
        return $this->morphTo();
    }

    public function authoredby()
    {
        return $this->morphTo();
    }
    
    public function answers()
    {
        return $this->morphMany(Answer::class,'answerable');
    }

    public function files()
    {
        return $this->morphToMany(File::class,'fileable');
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
    
    protected static function newFactory()
    {
        return RiddleFactory::new();
    }
}
