<?php

namespace App\YourEdu;

use App\YourEdu\ConversationAccount;
use Database\Factories\ConversationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use SoftDeletes,
        HasFactory;

    protected $fillable = ['type','state','account_type','description','name',
        'profile_url','conversation_id'];

    public function messages()
    {
        return $this->morphMany(Message::class, 'messageable');
    }

    public function learners()
    {
        return $this->morphedByMany(Learner::class,'accountable','conversationables');
    }

    public function parents()
    {
        return $this->morphedByMany(ParentModel::class,'accountable','conversationables');
    }

    public function professionals()
    {
        return $this->morphedByMany(Professional::class,'accountable','conversationables');
    }

    public function facilitators()
    {
        return $this->morphedByMany(Facilitator::class,'accountable','conversationables');
    }

    public function schools()
    {
        return $this->morphedByMany(School::class,'accountable','conversationables');
    }

    public function conversationAccounts()
    {
        return $this->hasMany(ConversationAccount::class);
    }

    public function accountableHavingUserId($userId)
    {
        return $this->conversationAccounts()
            ->whereHasMorph('accountable', '*', function($query, $type) use ($userId) {
                $query->whereUser($userId);
            })
            ->first()?->accountable;
    }

    public function accountableNotHavingUserId($userId)
    {
        return $this->conversationAccounts()
            ->whereHasMorph('accountable', '*', function($query, $type) use ($userId) {
                $query->whereNotUser($userId);
            })
            ->first()?->accountable;
    }

    public function hasSpecificConversationAccount($account)
    {
        return $this->conversationAccounts()
            ->where('accountable_type',$account::class)
            ->where('accountable_id', $account->id)
            ->exists();
    }

    public static function involvingBothAccounts($accountOne, $accountTwo)
    {
        return self::query()
            ->whereInvolvesBothAccounts($accountOne, $accountTwo)
            ->first();
    }

    public function scopeWhereInvolvesBothAccounts($query, $accountOne, $accountTwo)
    {
        return $query
            ->whereHas('conversationAccounts',function($query) use ($accountOne){
                $query->where([
                    'accountable_type' => get_class($accountOne),
                    'accountable_id' => $accountOne->id,
                ]);
            })->whereHas('conversationAccounts',function($query) use ($accountTwo){
                $query->where([
                    'accountable_type' => get_class($accountTwo),
                    'accountable_id' => $accountTwo->id,
                ]);
            });
    }

    protected static function newFactory()
    {
        return ConversationFactory::new();
    }
}
