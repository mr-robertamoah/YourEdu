<?php

namespace App\YourEdu;

use Database\Factories\ActivityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    //
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'description', 'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function addedby()
    {
        return $this->morphTo();
    }

    public function activityfor()
    {
        return $this->morphTo();
    }

    public function files()
    {
        return $this->morphToMany(File::class,'fileable');
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

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }
    
    public function allFiles()
    {
        $files = [];

        array_push($files, ...$this->images);
        array_push($files, ...$this->videos);
        array_push($files, ...$this->audios);
        array_push($files, ...$this->files);

        return $files;
    }

    public function doesntHaveFiles()
    {
        $count = 0;

        $count += $this->files->count();

        $count += $this->audios->count();

        $count += $this->videos->count();

        $count += $this->images->count();

        return $count < 1;
    }

    protected static function newFactory()
    {
        return ActivityFactory::new();
    }

}
