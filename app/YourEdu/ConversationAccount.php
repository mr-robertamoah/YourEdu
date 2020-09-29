<?php

namespace App\YourEdu;

use App\YourEdu\Conversation;
use Illuminate\Database\Eloquent\Model;

class ConversationAccount extends Model
{
    //

    protected $fillable = ['conversation_id','user_id','state'];

    protected $touches = ['conversation'];

    protected $table = 'conversationables';

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function accountable()
    {
        return $this->morphTo();
    }
}
