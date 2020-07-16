<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    //
    use SoftDeletes;

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
        return $this->morphedByMany(Question::class,'fileable','fileables'); 
    }

    public function answers()
    {
        return $this->morphedByMany(Answer::class,'fileable','fileables');
    }

    public function profiles()
    {
        return $this->morphedByMany(Profile::class,'fileable','fileables');
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class,'fileable','fileables');
    }

    public function comments()
    {
        return $this->morphedByMany(Comment::class,'fileable','fileables');
    }

    public function lessons()
    {
        return $this->morphedByMany(Lesson::class,'fileable','fileables');
    }

    public function lessonRequirements()
    {
        return $this->morphedByMany(LessonRequirement::class,'fileable','fileables');
    }

    public function books()
    {
        return $this->morphedByMany(Book::class,'fileable','fileables');
    }

    public function helps()
    {
        return $this->morphedByMany(Help::class,'fileable','fileables');
    }

    public function contribution()
    {
        return $this->morphedByMany(Contribution::class,'fileable','fileables');
    }

    public function permissions()
    {
        return $this->morphMany(Permission::class,'permissible');
    }
}
