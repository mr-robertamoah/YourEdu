<?php

namespace App\YourEdu;

use App\User;
use Database\Factories\AdminFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
{
    //
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id','name','role','level','title','description'
    ];

    public function verifications()
    {
        return $this->hasMany(Verification::class);
    }
    
    public function participants()
    {
        return $this->morphMany(Participant::class,'accountable');
    }

    public function secretQuestions()
    {
        return $this->hasMany(SecretQuestion::class);
    }

    public function activityTracks()
    {
       return $this->morphMany(ActivityTrack::class,'who');
    }

    public function savesMade()
    {
       return $this->morphMany(Save::class,'savedby');
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
    
    public function uniqueGradesAdded()
    {
        return $this->morphMany(Grade::class,'addedby');
    }

    public function addedClasses()
    {
        return $this->morphMany(ClassModel::class,'addedby');
    }

    public function addedPrograms()
    {
        return $this->morphMany(Program::class,'addedby');
    }

    public function attachments()
    {
        return $this->morphMany(PostAttachment::class,'attachedby');
    }

    public function aliasesAdded()
    {
        return $this->morphMany(Alias::class,'addedby');
    }

    public function uniqueSubjectsAdded()
    {
        return $this->morphMany(Subject::class,'addedby');
    }

    public function uniqueProgramsAdded()
    {
        return $this->morphMany(Program::class,'addedby');
    }

    public function uniqueCoursesAdded()
    {
        return $this->morphMany(Course::class,'addedby');
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
        return $this->morphMany(Question::class,'addedby');
    }

    public function activitiesAdded()
    {
        return $this->morphMany(Activity::class,'addedby');
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
    
    protected static function newFactory()
    {
        return AdminFactory::new();
    }
}
