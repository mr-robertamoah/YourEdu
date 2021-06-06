<?php

namespace App\YourEdu;

use App\User;
use Database\Factories\ParticipantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use SoftDeletes,
        HasFactory;

    protected $fillable =  ['user_id','state', 'accountable_type', 'accountable_id'];

    protected $touches =  ['participation'];

    public function accountable()
    {
        return $this->morphTo();
    }

    public function participation()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWhereAdmin($query)
    {
        return $query->where(function ($query) {
            $query->where('state', 'ADMIN');
        });
    }

    public function scopeWherePending($query)
    {
        return $query->where(function ($query) {
            $query->where('state', 'PENDING');
        });
    }

    public function scopeWhereParticipant($query, $account)
    {
        return $query->where(function($query) use ($account) {
            $query->where('accountable_type', $account::class)
                ->where('accountable_id', $account->id);
        });
    }

    public function scopeWhereParticipantByUserId($query, $userId)
    {
        return $query->where(function($query) use ($userId) {
            $query->whereHasMorph('accountable', '*', function($query) use ($userId) {
                $query->whereUser($userId);
            });
        });
    }

    public function scopeWhereDiscussionParticipation($query)
    {
        return $query->where('participation_type', "App\\YourEdu\\Discussion");
    }

    public function scopeWhereSpecificDiscussionParticipationById($query, $discussionId)
    {
        return $query
            ->where('participation_id', $discussionId)
            ->whereDiscussionParticipation();
    }

    public function scopeWhereAssessmentParticipation($query)
    {
        return $query->where('participation_type', "App\\YourEdu\\Assessment");
    }

    public function scopeWhereSpecificAssessmentParticipationById($query, $assessmentId)
    {
        return $query
            ->whereAssessmentParticipation()
            ->where('participation_id', $assessmentId);
    }

    protected static function newFactory()
    {
        return ParticipantFactory::new();
    }
}
