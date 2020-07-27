<?php

namespace App\YourEdu;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'user_id','name','role'
    ];

    public function verifications()
    {
        return $this->hasMany(Verification::class);
    }

    public function secretQuestions()
    {
        return $this->hasMany(SecretQuestion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schools()
    {
        return $this->belongsToMany(School::class)
            ->withPivot('level')
            ->withTimestamps();
    }

    public function curricula()
    {
        return $this->morphMany(Curriculum::class,'curriculable');
    }

    public function uniqueSubjectsAdded()
    {
        return $this->morphMany(Subject::class,'addedby');
    }
    
    public function images()
    {
        return $this->morphMany(Image::class,'ownedby');
    }
    
    public function files()
    {
        return $this->morphMany(File::class,'ownedby');
    }
    
    public function videos()
    {
        return $this->morphMany(Video::class,'ownedby');
    }
    
    public function audio()
    {
        return $this->morphMany(Audio::class,'ownedby');
    }

    public function groups()
    {
        return $this->morphToMany(Group::class,'groupable','groupables')
            // ->withPivot(['state','type','end_date'])
            ->withTimestamps();
    }

    public function groupsCreated()
    {
        return $this->morphMany(Group::class,'createdby');
    }

    public function flags()
    {
        return $this->hasMany(Flag::class);
    }

    public function bans()
    {
        return $this->hasMany(Ban::class);
    }
    
    public function contributionsMarked()
    {
        return $this->morphMany(Discussion::class,'markedby');
    }

    public function questionsAdded()
    {
        return $this->morphMany(Question::class,'questionedby');
    }

    public function activitiesAdded()
    {
        return $this->morphMany(Activity::class,'activityby');
    }
    
    public function addedImages()
    {
        return $this->morphMany(Image::class,'addedby');
    }
    
    public function addedFiles()
    {
        return $this->morphMany(File::class,'addedby');
    }
    
    public function addedVideos()
    {
        return $this->morphMany(Video::class,'addedby');
    }
    
    public function addedAudio()
    {
        return $this->morphMany(Audio::class,'addedby');
    }
}
