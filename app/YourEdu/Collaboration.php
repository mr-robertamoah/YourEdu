<?php

namespace App\YourEdu;

use App\Traits\CommissionTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collaboration extends Model
{
    //
    use SoftDeletes, CommissionTrait;

    const FREE = 'FREE';
    const PAID = 'PAID';

    protected $fillable = [
        'description', 'name', 'type'
    ];

    public function ownedLessons()
    {
        return $this->morphMany(Lesson::class,'ownedby');
    }

    public function collabos()
    {
        return $this->hasMany(Collabo::class);
    }

    public function deliveredLessons()
    {
        return $this->morphMany(Lesson::class,'lessonable');
    }

    public function addedby()
    {
        return $this->morphTo();
    }

    public function collaborationable()
    {
        return $this->morphTo();
    }

    public function commissions()
    {
        return $this->morphMany(Commission::class,'for');
    }

    public function courses()
    {
        return $this->morphToMany(Course::class,'coursable','coursables')
            ->withPivot(['activity','resource']);
    }

    public function facilitators()
    {
        return $this->morphedByMany(Facilitator::class,'collaborationable','collabo')
            ->withPivot(['state'])->withTimestamps();
    }

    public function professionals()
    {
        return $this->morphedByMany(Professional::class,'collaborationable','collabo')
            ->withPivot(['state'])->withTimestamps();
    }

    public function collaborators()
    {
        return $this->facilitators->merge($this->professionals);
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

    public function scopeWhereCollaborationable($query,$account)
    {
        return $query->where(function($query) use ($account) {
            $query->whereHas('collabos', function($query) use ($account) {
                $query->whereHasMorph('collaborationable', function($query) use ($account) {
                    $query->where('user_id', $account->user_id);
                });
            });
        });
    }
}
