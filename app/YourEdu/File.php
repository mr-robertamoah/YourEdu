<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    //
    use SoftDeletes;
    
    protected $fillable = [
        'path', 'mime', 'size','name'
    ];
    
    protected $appends = [
        'url',
    ];

    public function getUrlAttribute()
    {
        return asset($this->path);
    }

    public function ownedby()
    {
        return $this->morphTo();
    }

    public function addedby()
    {
        return $this->morphTo();
    }

    public function questions()
    {
        return $this->morphedByMany(Question::class,'fileable')
        ->withPivot(['state'])->withTimestamps(); 
    }

    public function answers()
    {
        return $this->morphedByMany(Answer::class,'fileable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function profiles()
    {
        return $this->morphedByMany(Profile::class,'fileable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class,'fileable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function comments()
    {
        return $this->morphedByMany(Comment::class,'fileable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function lessons()
    {
        return $this->morphedByMany(Lesson::class,'fileable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function lessonRequirements()
    {
        return $this->morphedByMany(LessonRequirement::class,'fileable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function books()
    {
        return $this->morphedByMany(Book::class,'fileable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function helps()
    {
        return $this->morphedByMany(Help::class,'fileable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function contribution()
    {
        return $this->morphedByMany(Contribution::class,'fileable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function permissions()
    {
        return $this->morphMany(Permission::class,'permissible');
    }
}
