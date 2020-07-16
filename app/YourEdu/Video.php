<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
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
        return $this->morphedByMany(Answer::class,'videoable','videoables');
    }

    public function profiles()
    {
        return $this->morphedByMany(Profile::class,'videoable','videoables');
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class,'videoable','videoables');
    }

    public function comments()
    {
        return $this->morphedByMany(Comment::class,'videoable','videoables');
    }

    public function helps()
    {
        return $this->morphedByMany(Help::class,'videoable','videoables');
    }

    public function activitiess()
    {
        return $this->morphedByMany(Activity::class,'videoable','videoables');
    }

    public function lessons()
    {
        return $this->morphedByMany(Lesson::class,'videoable','videoables');
    }

    public function lessonRequirements()
    {
        return $this->morphedByMany(LessonRequirement::class,'videoable','videoables');
    }

    public function books()
    {
        return $this->morphedByMany(Book::class,'videoable','videoables');
    }

    public function contribution()
    {
        return $this->morphedByMany(Contribution::class,'videoable','videoables');
    }

    public function permissions()
    {
        return $this->morphMany(Permission::class,'permissible');
    }

    
}
