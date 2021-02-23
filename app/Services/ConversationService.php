<?php

namespace App\Services;

use App\Events\UpdatedChatItemsState;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\ConversationAccountNotFoundException;
use \Debugbar;

class ConversationService
{
    
    public function getMessages($conversationId, $id)
    {
        $conversation = getYourEduModel('conversation',$conversationId);

        if (is_null($conversation)) {
            throw new AccountNotFoundException("unsuccessful, conversation was not found for id {$conversationId}.");
        }

        $this->findUserAccount($conversation, $id);
        $this->markMessagesQuestionsSeen($conversation, $id);

        $chats = $conversation->messages()->with(['images','videos','audios','files'])
            ->get();
        $chats = $chats->merge($conversation->questions()
            ->with(['images','videos','audios','files'])
            ->get());

        broadcast(new UpdatedChatItemsState($conversationId, 
            $this->findOtherUserId($conversation, $id)));
        return $chats;
    }

    private function findOtherUserId($conversation, $id)
    {
        return $conversation->conversationAccounts()
            ->where('user_id','!=',$id)->first()->user_id;
    }

    private function findUserAccount($conversation, $id)
    {
        $userAccount = $conversation->conversationAccounts()
            ->where('user_id',$id)->first();

        if (is_null($userAccount)) {
            throw new ConversationAccountNotFoundException("user id {$id} not a valid account for conversation with id {$conversation->id}");
        }
    }

    private function markMessagesQuestionsSeen($conversation, $id)
    {
        $chatItems = $conversation->messages()->where('to_user_id', $id)
            ->where('state','!=','SEEN')->get();
        $chatItems = $chatItems->merge($conversation->questions()
            ->whereHasMorph('questionedby','*',function($query) use ($id){
                $query->where('user_id','!=',$id);
            })->where('state','!=','SEEN')->get());
        
        foreach ($chatItems as $chatItem) {
            $chatItem->state = 'SEEN';
            $chatItem->timestamps = false;
            $chatItem->save();
        }
    }
}

?>