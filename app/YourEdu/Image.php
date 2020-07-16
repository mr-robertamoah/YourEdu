<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
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
        return $this->morphedByMany(Question::class,'imageable','imageables');
    }

    public function answers()
    {
        return $this->morphedByMany(Answer::class,'imageable','imageables');
    }

    public function profiles()
    {
        return $this->morphedByMany(Profile::class,'imageable','imageables');
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class,'imageable','imageables');
    }

    public function comments()
    {
        return $this->morphedByMany(Comment::class,'imageable','imageables');
    }

    public function lessons()
    {
        return $this->morphedByMany(Lesson::class,'imageable','imageables');
    }

    public function acitivities()
    {
        return $this->morphedByMany(Activity::class,'imageable','imageables');
    }

    public function lessonRequirements()
    {
        return $this->morphedByMany(LessonRequirement::class,'imageable','imageables');
    }

    public function books()
    {
        return $this->morphedByMany(Book::class,'imageable','imageables');
    }

    public function helps()
    {
        return $this->morphedByMany(Help::class,'imageable','imageables');
    }

    public function contribution()
    {
        return $this->morphedByMany(Contribution::class,'imageable','imageables');
    }

    public function permissions()
    {
        return $this->morphMany(Permission::class,'permissible');
    }
}
