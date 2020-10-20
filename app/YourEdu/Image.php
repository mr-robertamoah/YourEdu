<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
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
        return asset("assets/{$this->path}");
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
        return $this->morphedByMany(Question::class,'imageable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function answers()
    {
        return $this->morphedByMany(Answer::class,'imageable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function profiles()
    {
        return $this->morphedByMany(Profile::class,'imageable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class,'imageable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function comments()
    {
        return $this->morphedByMany(Comment::class,'imageable')
            ->withPivot(['state'])
            ->withTimestamps();
    }

    public function lessons()
    {
        return $this->morphedByMany(Lesson::class,'imageable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function acitivities()
    {
        return $this->morphedByMany(Activity::class,'imageable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function lessonRequirements()
    {
        return $this->morphedByMany(LessonRequirement::class,'imageable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function books()
    {
        return $this->morphedByMany(Book::class,'imageable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function helps()
    {
        return $this->morphedByMany(Help::class,'imageable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function contribution()
    {
        return $this->morphedByMany(Contribution::class,'imageable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function permissions()
    {
        return $this->morphMany(Permission::class,'permissible');
    }
}
