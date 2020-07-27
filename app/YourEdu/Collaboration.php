<?php

namespace App\YourEdu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collaboration extends Model
{
    //
    use SoftDeletes;

    public function ownedLessons()
    {
        return $this->morphMany(Lesson::class,'ownedby');
    }

    public function deliveredLessons()
    {
        return $this->morphMany(Lesson::class,'lessonable');
    }

    public function ownedby()
    {
        return $this->morphTo();
    }

    public function collaborationable()
    {
        return $this->morphTo();
    }

    public function facilitators()
    {
        return $this->morphedByMany(Facilitator::class,'collaborationable','collabo');
    }

    public function professionals()
    {
        return $this->morphedByMany(Professional::class,'collaborationable','collabo');
    }

    public function extracurriculums()
    {
        return $this->morphMany(Extracurriculum::class,'extrable');
    }
    
    public function requests()
    {
        return $this->morphMany(Request::class,'requestable');
    }

    public function shares()
    {
        return $this->morphMany(Share::class,'shareable');
    }

    public function questionsOwned()
    {
        return $this->morphMany(Question::class,'owned');
    }

    public function activitiesOwned()
    {
        return $this->morphMany(Activity::class,'owned');
    }
}
