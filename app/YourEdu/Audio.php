<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Audio extends Model
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
        return $this->morphedByMany(Question::class,'audioable','audioables');
    }

    public function answers()
    {
        return $this->morphedByMany(Answer::class,'audioable','audioables');
    }

    public function profiles()
    {
        return $this->morphedByMany(Profile::class,'audioable','audioables');
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class,'audioable','audioables');
    }

    public function comments()
    {
        return $this->morphedByMany(Comment::class,'audioable','audioables');
    }

    public function lessons()
    {
        return $this->morphedByMany(Lesson::class,'audioable','audioables');
    }

    public function activities()
    {
        return $this->morphedByMany(Activity::class,'audioable','audioables');
    }

    public function lessonRequirements()
    {
        return $this->morphedByMany(LessonRequirement::class,'audioable','audioables');
    }

    public function books()
    {
        return $this->morphedByMany(Book::class,'audioable','audioables');
    }

    public function contribution()
    {
        return $this->morphedByMany(Contribution::class,'audioable','audioables');
    }

    public function permissions()
    {
        return $this->morphMany(Permission::class,'permissible');
    }
}
