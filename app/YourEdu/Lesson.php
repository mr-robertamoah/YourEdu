<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    //
    use SoftDeletes;

    public function acitvities(){
        return $this->morphMany(Activity::class,'activityfor');
    }

    public function likes(){
        return $this->morphMany(Like::class,'likeable');
    }

    public function objectives()
    {
        return $this->morphMany(Objective::class,'objectiveable');
    }

    public function beenSaved()
    {
       return $this->morphMany(Save::class,'saveable');
    }

    public function books()
    {
        return $this->morphMany(Book::class,'bookable');
    }

    public function poems()
    {
        return $this->morphMany(Poem::class,'poemable');
    }

    public function riddles()
    {
        return $this->morphMany(Riddle::class,'riddleable');
    }

    public function summary()
    {
        return $this->morphOne(Summary::class,'summariable');
    }

    public function lessonRequirements()
    {
        return $this->hasMany(LessonRequirement::class);
    }

    public function ownedby()
    {
        return $this->morphTo();
    }

    public function lessonable()
    {
        return $this->morphTo();
    }

    public function curriculumable()
    {
        return $this->morphTo();
    }

    public function previousLesson()
    {
        return $this->belongsTo(Lesson::class,'previous_lesson_id');
    }

    public function nextLesson()
    {
        return $this->hasOne(Lesson::class,'previous_lesson_id');
    }

    public function curriculumStructure()
    {
        return $this->belongsTo(CurriculumStructure::class,'stucture_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class,'class_id');
    }

    public function classSection()
    {
        return $this->belongsTo(ClassSection::class);
    }

    public function collaboration()
    {
        return $this->morphOne(Collaboration::class,'collaborationable');
    }

    public function assessments()
    {
        return $this->morphMany(Assessment::class,'assessmentable');
    }

    public function links()
    {
        return $this->morphMany(Link::class,'linkable');
    }

    public function files()
    {
        return $this->morphToMany(File::class,'fileable')
        ->withPivot(['state'])->withTimestamps();
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

    public function flags()
    {
        return $this->morphMany(Flag::class,'flaggable');
    }

    public function permissions()
    {
        return $this->morphMany(Permission::class,'permissible');
    }
    
    public function discussion()
    {
        return $this->morphOne(Discussion::class,'discussionon');
    }
}
