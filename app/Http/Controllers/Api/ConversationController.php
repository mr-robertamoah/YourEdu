<?php

namespace App\Http\Controllers\Api;

use App\Events\ConversationResponse;
use App\Events\DeleteChatMessage;
use App\Events\NewChatAnswer;
use App\Events\NewChatMark;
use App\Events\NewChatMessage;
use App\Events\NewChatQuestion;
use App\Events\NewConversation;
use App\Events\UpdatedChatItemState;
use App\Http\Controllers\Controller;
use App\Http\Resources\AnswerResource;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\ChatQuestionResource;
use App\Http\Resources\MessageQuestionResource;
use App\Services\AnswerService;
use App\Services\ConversationService;
use App\Services\FileService;
use App\Services\MarkService;
use App\Services\MessageService;
use App\DTOs\QuestionDTO;
use App\Services\QuestionService;
use App\YourEdu\Answer;
use App\YourEdu\Conversation;
use App\YourEdu\Follow;
use App\YourEdu\Message;
use App\YourEdu\Question;
use \Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    //

    public function updateItemState(Request $request)
    {
        $mainItem = getYourEduModel($request->item, $request->itemId);

        if (is_null($mainItem)) {
            return response()->json([
                'message' => "unsuccessful, {$request->item} does not exist"
            ], 422);
        }

        $mainItem->setTouchedRelations([]);
        $mainItem->timestamps = false;
        $mainItem->state = $request->state;
        $mainItem->save();

        $itemResource = new MessageQuestionResource($mainItem);
        broadcast(new UpdatedChatItemState($request->item,$itemResource,$request->userId, $request->conversationId));
        return response()->json([
            'message' => "successful",
            'chatItem' => $itemResource
        ]);
    }

    public function deleteItem(Request $request)
    {
        try {
            DB::beginTransaction();
            $mainItem = null;
            $id = auth()->id();
            if ($request->item === 'message') {
                $mainItem = (new MessageService())
                    ->deleteMessage($id,$request->itemId,$request->action);
            } else if ($request->item === 'question') {
                $mainItem = (new QuestionService())
                    ->deleteQuestion(QuestionDTO::createFromData(
                        
                    ));
            }
            
            DB::commit();
            if ($request->action === 'delete') {
                broadcast(new DeleteChatMessage($mainItem['item'], $mainItem['itemId'], 
                    $request->conversationId,$request->userId));
                return response()->json([
                    'message' => 'successful'
                ]);
            }

            return response()->json([
                'message' => 'successful',
                'chatItem' => new MessageQuestionResource($mainItem)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function getMessages($conversationId)
    {
        $chats = (new ConversationService())->getMessages($conversationId, auth()->id());

        return MessageQuestionResource::collection(paginate($chats->sortByDesc('updated_at'),10));
    }

    public function markAnswer(Request $request)
    {
        $answer = getYourEduModel('answer', $request->answerId);
        $account = getYourEduModel($request->account, $request->accountId);

        if (is_null($answer) || is_null($account)) {
            return response()->json([
                'message' => 'unsuccessful, account or answer does not exist'
            ], 422);
        }

        try {
            DB::beginTransaction();
            $mark = (new MarkService())->createMark($request,$account,$answer);
            if (is_null($mark)) {
                return response()->json([
                    'message' => "unsuccessful, mark was not created",
                ],422);
            }
            DB::commit();
            $answerResource = new AnswerResource(Answer::find($answer->id)
                ->load('images','videos','audios','files'));
            $questionResource = new ChatQuestionResource(Question::find($request->questionId)
                ->load('images','videos','audios','files'));
            broadcast(new NewChatMark($questionResource, $request->chattingUserId));
            return response()->json([
                'message' => "successful",
                'chatAnswer' => $answerResource
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return response()->json([
                'message' => "unsuccessful, something unfortunate happened",
            ],422);
        }
    }

    public function sendAnswer(Request $request)
    {
        $question = Question::find($request->questionId);

        if (is_null($question)) {
            return response()->json([
                'message' => 'unsuccessful, question does not exist'
            ], 422);
        }

        $account = getYourEduModel($request->account, $request->accountId);

        if (is_null($account)) {
            return response()->json([
                'message' => 'unsuccessful, account does not exist'
            ], 422);
        }

        try {
            DB::beginTransaction();
            $answer = (new AnswerService())->createAnswer($request,$account,$question,true);
    
            if (is_null($answer)) {
                return response()->json([
                    'message' => 'unsuccessful, answer was not created.'
                ], 422);
            }

            DB::commit();
            $questionResource = new ChatQuestionResource(Question::find($question->id));
            broadcast(new NewChatAnswer($questionResource, $request->chattingUserId));
            return response()->json([
                'message' => 'successful',
                'chatQuestion' => $questionResource
            ]);
            
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function sendQuestion(Request $request, $conversationId)
    {
        $request->validate([
            'question' => 'required|string',
            'score' => 'nullable',
            'possibleAnswers' => 'nullable|string',
            'file' => 'nullable|file',
        ]);

        $conversation = getYourEduModel('conversation',$conversationId);
        $account = getYourEduModel($request->account,$request->accountId);

        if (is_null($account)) {
            return response()->json([
                'message' => 'unsuccessful, one of the account does not exist.'
            ], 422);
        }
        
        if (is_null($conversation)) {
            return response()->json([
                'message' => 'unsuccessful, conversation does not exist.'
            ], 422);
        }

        try {
            DB::beginTransaction();
            $questionDTO = QuestionDTO::createFromRequest($request);
            $questionDTO->questionable = $conversation;
            $questionDTO->addedby = $account;
            $questionDTO->state = 'SENT';
            $question = (new QuestionService)->createQuestion($questionDTO);
            
            if ($request->has('file')) {
                //account has uploaded a file and that file is attached to a question
                $this->accountCreateFile($request, $account, $question);
            }
    
            DB::commit();
            $question = Question::find($question->id)->load('images','videos','audios','files');
            $questionResource = new ChatQuestionResource($question);

            broadcast(new NewChatQuestion($questionResource,$request->chattingUserId))->toOthers();
            return response()->json([
                'message' => 'successful',
                'chatQuestion' => $questionResource
            ]);
        } catch (\Throwable $th) {
            DB::commit();
            throw $th;
            return response()->json([
                'message' => 'unsuccessful, something happened.'
            ], 422);
        }
    }

    public function sendMessage(Request $request,$conversationId)
    {
        try {
            DB::beginTransaction();
            $messageData = (new MessageService())->createMessage('conversation',$conversationId,
                $request->account,$request->accountId,auth()->id(),$request->message,'sent',
                $request->file('file'),$request->chattingAccount,
                $request->chattingAccountId,$request->chattingUserId);

            broadcast(new NewChatMessage(
                new MessageResource($messageData['message'])));
            DB::commit();
            return response()->json([
                'message' => 'successful',
                'chatMessage' => new MessageResource($messageData['message'])
            ]);
        } catch (\Throwable $th) {
            DB::commit();
            throw $th;
            return response()->json([
                'message' => 'unsuccessful, something happened.'
            ], 422);
        }
    }

    private function accountCreateFile($request, $account, $item)
    {
        $fileDetails = FileService::getFileDetails($request->file('file'));
    
        $uploadedFile = FileService::accountCreateFile($account,$fileDetails,$item);
        $uploadedFile->ownedby()->associate($account);
        $uploadedFile->save();
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
        $account = getYourEduModel($request->account, $request->accountId);

        if (is_null($account)) {
            return response()->json([
                'message' => 'unsuccessful, your account does not exist.'
            ], 422);
        }
        
        $chatAccount = getYourEduModel($request->chatAccount,$request->chatAccountId);

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
        $account = getYourEduModel($request->account, $request->accountId);
        $chattingAccount = getYourEduModel($request->chattingAccount, $request->chattingAccountId);

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
        $accountOne = getYourEduModel($request->accountOne, $request->accountOneId);
        $accountTwo = getYourEduModel($request->accountTwo, $request->accountTwoId);
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
        $account = getYourEduModel($request->account, $request->accountId);
        $chattingAccount = getYourEduModel($request->chattingAccount, $request->chattingAccountId);
        
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
