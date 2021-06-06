<?php

namespace App\Http\Controllers\Api;

use App\DTOs\AnswerDTO;
use App\DTOs\ConversationDTO;
use App\DTOs\MessageDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\AnswerResource;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Services\ConversationService;
use App\DTOs\QuestionDTO;
use App\Http\Resources\MarkResource;
use \Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    public function updateMessageState(Request $request)
    {
        try {
            DB::beginTransaction();

            $message = (new ConversationService)->updateMessageState(
                MessageDTO::createFromRequest($request)
            );
            
            DB::commit();
            
            return response()->json([
                'message' => "successful",
                'chatMessage' => new MessageResource($message)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function deleteMessage(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $message = (new ConversationService)->deleteMessage(
                MessageDTO::createFromRequest($request)
            );
            
            DB::commit();

            return response()->json([
                'message' => 'successful',
                'chatMessage' => $message ?
                    new MessageResource($message) : null
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function getMessages(Request $request)
    {
        $messages = (new ConversationService)->getMessages(
            ConversationDTO::createFromRequest($request)
        );

        return MessageResource::collection($messages);
    }

    public function markAnswer(Request $request)
    {
        try {
            DB::beginTransaction();

            $mark = (new ConversationService())->markAnswer(
                AnswerDTO::createFromData(
                    answerId: $request->answerId,
                    account: $request->account,
                    accountId: $request->accountId,
                    userId: $request->user()?->id,
                )
                ->addMarkData($request->markData)
            );

            DB::commit();
            
            return response()->json([
                'message' => "successful",
                'mark' => new MarkResource($mark)
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
        try {
            DB::beginTransaction();

            $answer = (new ConversationService)->sendAnswer(
                QuestionDTO::createFromData(
                    questionId: $request->questionId,
                    userId: $request->user()->id,
                    account: $request->account,
                    accountId: $request->accountId,
                )
                ->addAnswerData($request->answerData)
                ->addAnswerFiles($request->file('answerFiles'))
            );

            DB::commit();
            
            return response()->json([
                'message' => 'successful',
                'answer' => new AnswerResource($answer)
            ]);
            
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function sendMessage(Request $request)
    {
        try {
            DB::beginTransaction();

            $message = (new ConversationService)->sendMessage(
                MessageDTO::createFromRequest($request)
                    ->addData(
                        item: 'conversation',
                        itemId: $request->conversationId,
                        state: 'sent',
                    )
                    ->addFile(
                        $request->file('file')
                    )
                    ->addQuestionData($request->questionData)
                    ->addQuestionFiles($request->file('questionFiles'))
            );

            DB::commit();
            
            return response()->json([
                'message' => 'successful',
                'chatMessage' => new MessageResource($message)
            ]);
        } catch (\Throwable $th) {
            DB::commit();
            throw $th;
            return response()->json([
                'message' => 'unsuccessful, something happened.'
            ], 422);
        }
    }

    public function getConversations(Request $request)
    {
        $conversations = (new ConversationService)->getConversations(
            ConversationDTO::createFromRequest($request)
        );

        return ConversationResource::collection($conversations);
    }

    public function getBlockedConversations(Request $request)
    {
        $conversations = (new ConversationService)->getBlockedConversations(
            ConversationDTO::createFromRequest($request)
        );

        return ConversationResource::collection($conversations);
    }

    public function getPendingConversations(Request $request)
    {
        $conversations = (new ConversationService)->getPendingConversations(
            ConversationDTO::createFromRequest($request)
        );

        return ConversationResource::collection($conversations);
    }

    public function createConversation(Request $request)
    {
        try {
            DB::beginTransaction();

            $conversation = (new ConversationService)->createConversation(
                ConversationDTO::createFromRequest($request)
            );

            return response()->json([
                'message' => 'successful',
                'conversation' => new ConversationResource($conversation),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return response()->json([
                'message' => 'unsuccessful, something happened'
            ], 422);
        }
        
    }

    public function createConversationResponse(Request $request)
    {
        try {
            DB::beginTransaction();
            
            (new ConversationService)->createConversationResponse(
                ConversationDTO::createFromRequest($request)
            );

            DB::commit();
            
            return response()->json([
                'message' => 'successful',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return response()->json([
                'message' => 'unsuccessful, something unfortunate happened.'
            ], 422);
        }
    }

    public function unblockConversation(Request $request,$conversationId)
    {
        try {
            DB::beginTransaction();

            (new ConversationService)->unblockConversation(
                ConversationDTO::createFromRequest($request)
            );

            DB::commit();
            
            return response()->json([
                'message' => 'successful',
            ]);

        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return response()->json([
                'message' => 'unsuccessful, something unfortunate happened'
            ],422);
        }
    }

    public function blockConversation(Request $request)
    {
        try {
            DB::beginTransaction();

            (new ConversationService)->blockConversation(
                ConversationDTO::createFromRequest($request)
            );
            
            DB::commit();
            
            return response()->json([
                'message' => 'successful',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return response()->json([
                'message' => 'unsuccessful, something unfortunate happened'
            ],422);
        }
        
    }
}
