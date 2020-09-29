<?php

namespace App\Http\Controllers\Api;

use App\Events\ConversationResponse;
use App\Events\NewChatMessage;
use App\Events\NewConversation;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConversationAccountResource;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\YourEdu\Conversation;
use App\YourEdu\Facilitator;
use App\YourEdu\Follow;
use App\YourEdu\Learner;
use App\YourEdu\Message;
use App\YourEdu\ParentModel;
use App\YourEdu\Professional;
use \Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ConversationController extends Controller
{
    //

    public function getMessages($conversationId)
    {
        $conversation = Conversation::find($conversationId);

        if (is_null($conversation)) {
            return response()->json([
                'message' => 'unsuccessful, conversation does not exist.'
            ], 422);
        }

        $messages = $conversation->messages()->with(['images','videos','audios','files'])
            ->latest()->paginate(10);
        return MessageResource::collection($messages);
    }

    public function sendMessage(Request $request,$conversationId)
    {
        $conversation = getAccountObject('conversation',$conversationId);
        $toable = getAccountObject($request->account,$request->accountId);
        $fromable = getAccountObject($request->chattingAccount,$request->chattingAccountId);

        if (is_null($toable) || is_null($fromable)) {
            return response()->json([
                'message' => 'unsuccessful, one of the accounts does not exist.'
            ], 422);
        }
        
        if (is_null($conversation)) {
            return response()->json([
                'message' => 'unsuccessful, conversation does not exist.'
            ], 422);
        }

        try {
            DB::beginTransaction();
            $message = $conversation->messages()->create([
                'message' => $request->message,
                'to_user_id' => $request->chattingUserId,
                'from_user_id' => auth()->id(),
                'state' => 'SENT',
            ]);
    
            $message->toable()->associate($toable);
            $message->fromable()->associate($fromable);
            $message->save();
    
            if ($request->has('file')) {
                //fromable has uploaded a file and that file is attached to a message
                $fileDetails = getFileDetails($request->file('file'));
    
                $uploadedFile = accountCreateFile($fromable,$fileDetails,$message);
                $uploadedFile->ownedby()->associate($fromable);
                $uploadedFile->save();
    
            }
    
            $message = Message::find($message->id)->load('images','videos','audios','files');
            $messageResource = new MessageResource($message);

            broadcast(new NewChatMessage($messageResource))->toOthers();
            DB::commit();
            return response()->json([
                'message' => 'successful',
                'chatMessage' => $messageResource
            ]);
        } catch (\Throwable $th) {
            DB::commit();
            throw $th;
            return response()->json([
                'message' => 'unsuccessful, something happened.'
            ], 422);
        }
    }

    private function getPaginatedConversations($states)
    {
        return  Conversation::with('conversationAccounts.accountable.profile.images')
            ->whereHas('conversationAccounts', function($query) use ($states){
            $query->where(function($q) use ($states){
                $q->where('user_id',auth()->id())->whereIn('state',$states);
            });
        })->orderBy('updated_at','desc')->paginate(10);
    }

    public function getConversations()
    {
        $conversations = $this->getPaginatedConversations(['ACCEPT','REQUEST']);

        return ConversationResource::collection($conversations);
    }

    public function getBlockedConversations()
    {
        $conversations = $this->getPaginatedConversations(['BLOCK','DECLINE']);

        return ConversationResource::collection($conversations);
    }

    public function getPendingConversations()
    {
        $conversations = $this->getPaginatedConversations(['PENDING']);

        return ConversationResource::collection($conversations);
    }

    public function createConversation(Request $request)
    {
        $account = getAccountObject($request->account, $request->accountId);

        if (is_null($account)) {
            return response()->json([
                'message' => 'unsuccessful, your account does not exist.'
            ], 422);
        }
        
        $chatAccount = getAccountObject($request->chatAccount,$request->chatAccountId);

        if (is_null($chatAccount)) {
            return response()->json([
                'message' => 'unsuccessful, other user account does not exist.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $checkConversation =  Conversation::whereHas('conversationAccounts',function($query) use ($chatAccount){
                $query->where([
                    'accountable_type' => get_class($chatAccount),
                    'accountable_id' => $chatAccount->id,
                ]);
            })->whereHas('conversationAccounts',function($query) use ($account){
                $query->where([
                    'accountable_type' => get_class($account),
                    'accountable_id' => $account->id,
                ]);
            })->first();

            if (!is_null($checkConversation)) {
                $this->updateFollowConversation($chatAccount,$account, 'conversation_id', $checkConversation->id);
                $conversation = Conversation::find($checkConversation->id)
                    ->load('conversationAccounts.accountable.profile.images');
                DB::commit();
                return response()->json([
                    'message' => 'successful',
                    'conversation' => new ConversationResource($conversation)
                ]);
            }

            $conversation = Conversation::create([
                'type' => 'PRIVATE'
            ]);
    
            if (is_null($conversation)) {
                return response()->json([
                    'message' => 'unsuccessful, could not create converation'
                ], 422);
            }

            $conversationAccount = $conversation->conversationAccounts()->create([
                'user_id' => $request->userId,
                'state' => 'REQUEST'
            ]);
            $follows = $this->updateFollowConversation($chatAccount,$account,'conversation_id', $conversation->id);
            $conversationAccount->accountable()->associate($account);
            $conversationAccount->save();
            
            $conversationAccount = $conversation->conversationAccounts()->create([
                'user_id' => $request->otherUserId,
                'state' => 'PENDING'
            ]);
            $conversationAccount->accountable()->associate($chatAccount);
            $conversationAccount->save();

            Debugbar::info($follows);
            Debugbar::info($conversationAccount);
            Debugbar::info($account);

            DB::commit();
            $conversation = Conversation::find($conversation->id)
                ->load('conversationAccounts.accountable.profile.images');
            $conversationResource = new ConversationResource($conversation);
            broadcast(new NewConversation($conversationResource, $chatAccount->user_id));
            return response()->json([
                'message' => 'successful',
                'conversation' => $conversationResource,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return response()->json([
                'message' => 'unsuccessful, something happened'
            ], 422);
        }
        
    }

    private function updateFollowConversation($accountOne, $accountTwo, $dataType, $data)
    {
        $follows = Follow::where(function($query) use ($accountOne, $accountTwo){
            $query->where([
                'followable_type' => get_class($accountOne),
                'followable_id' => $accountOne->id,
                'followedby_type' => get_class($accountTwo),
                'followedby_id' => $accountTwo->id,
            ]);
        })->orWhere(function($query) use ($accountOne, $accountTwo){
            $query->where([
                'followable_type' => get_class($accountTwo),
                'followable_id' => $accountTwo->id,
                'followedby_type' => get_class($accountOne),
                'followedby_id' => $accountOne->id,
            ]); })->get();

        if (is_null($follows)) {
            return null;
        }
        foreach ($follows as $f) {
            if ($dataType === 'conversation_id') {
                $f->conversation_id = $data;
            } else if ($dataType === 'chat_status') {
                $id = auth()->id();
                if ($f->followed_user_id === $id) {
                    $f->followable_chat_status = $data;
                } else if ($f->user_id === $id) {
                    $f->followedby_chat_status = $data;
                }
            }
            
            $f->save();
        }

        return $follows;
    }

    public function createConversationResponse(Request $request)
    {
        $account = getAccountObject($request->account, $request->accountId);
        $chattingAccount = getAccountObject($request->chattingAccount, $request->chattingAccountId);

        if (is_null($account)) {
            return response()->json([
                'message' => 'unsuccessful, the chatting account does not exist.'
            ], 422);
        }

        $conversation = Conversation::with('conversationAccounts')
            ->find($request->conversationId);

        if (is_null($conversation)) {
            return response()->json([
                'message' => 'unsuccessful, conversation does not exist.'
            ], 422);
        }

        $conversationAccount = $conversation->conversationAccounts()
            ->where('accountable_type',get_class($account))
            ->where('accountable_id', $request->accountId)->first();

        if (is_null($conversationAccount)) {
            return response()->json([
                'message' => 'unsuccessful, the chatting account does not exist.'
            ], 422);
        }

        try {
            DB::beginTransaction();
            $conversationAccount->update([
                'state' => $request->response
            ]);

            $this->updateFollowConversation($account,$chattingAccount,'chat_status',$request->response);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return response()->json([
                'message' => 'unsuccessful, something unfortunate happened.'
            ], 422);
        }

        DB::commit();
        $conversation = Conversation::with(['conversationAccounts.accountable.profile.images'])->find($conversation->id);
        $conversationResource = new ConversationResource($conversation);
        broadcast(new ConversationResponse($conversationResource,$chattingAccount->user_id,$request->response));
        return response()->json([
            'message' => 'successful',
            'conversation' => $conversationResource
        ]);
    }

    public function unblockConversation(Request $request,$conversationId)
    {
        $accountOne = getAccountObject($request->accountOne, $request->accountOneId);
        $accountTwo = getAccountObject($request->accountTwo, $request->accountTwoId);
        $userId = auth()->id();
        $otherUserId = null;

        if ($accountOne->user_id === $userId) {
            $otherUserId = $accountTwo->user_id;
        } else if ($accountTwo->user_id === $userId) {
            $otherUserId = $accountOne->user_id;
        }
        $conversation = Conversation::with('messages')->find($conversationId);
        if (is_null($conversation)) {
            return response()->json([
                'message' => 'unsuccessful, conversation does not exist'
            ],422);
        }

        $conversationAccount =  $conversation->conversationAccounts()
            ->where('user_id',$userId)->first();
        if (is_null($conversationAccount)) {
            return response()->json([
                'message' => 'unsuccessful, conversation account does not exist'
            ],422);
        }

        try {
            DB::beginTransaction();
            $conversationAccount->state = 'ACCEPT';
            $conversationAccount->save();

            $this->updateFollowConversation($accountOne,$accountTwo,'chat_status','ACCEPT');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return response()->json([
                'message' => 'unsuccessful, something unfortunate happened'
            ],422);
        }

        DB::commit();
        $conversation = Conversation::with(['conversationAccounts.accountable.profile.images'])->find($conversation->id);
        $conversationResource = new ConversationResource($conversation);
        broadcast(new ConversationResponse($conversationResource,$otherUserId,'ACCEPT'));
        return response()->json([
            'message' => 'successful',
            'conversation' => $conversationResource
        ]);
    }

    public function blockConversation(Request $request,$conversationId)
    {
        $account = getAccountObject($request->account, $request->accountId);
        $chattingAccount = getAccountObject($request->chattingAccount, $request->chattingAccountId);
        
        $conversation = Conversation::find($conversationId);
        if (is_null($conversation)) {
            return response()->json([
                'message' => 'unsuccessful, conversation does not exist'
            ],422);
        }

        $conversationAccount =  $conversation->conversationAccounts()
            ->where('user_id',auth()->id())->first();
        if (is_null($conversationAccount)) {
            return response()->json([
                'message' => 'unsuccessful, conversation account does not exist'
            ],422);
        }

        try {
            DB::beginTransaction();
            $conversationAccount->state = 'BLOCK';
            $conversationAccount->save();

            $this->updateFollowConversation($account,$chattingAccount,'chat_status','BLOCK');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return response()->json([
                'message' => 'unsuccessful, something unfortunate happened'
            ],422);
        }

        DB::commit();
        $conversation = Conversation::with(['conversationAccounts.accountable.profile.images'])->find($conversation->id);
        $conversationResource = new ConversationResource($conversation);
        broadcast(new ConversationResponse($conversationResource,$chattingAccount->user_id,'BLOCK'));
        return response()->json([
            'message' => 'successful',
            'conversation' => $conversationResource
        ]);
    }
}
