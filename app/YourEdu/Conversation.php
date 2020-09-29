<?php

namespace App\YourEdu;

use App\YourEdu\ConversationAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['type','state','account_type','description','name',
        'profile_url','conversation_id'];

    public function messages()
    {
        return $this->hasMany(Message::class);
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
}
