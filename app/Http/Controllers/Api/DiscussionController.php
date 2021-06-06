<?php

namespace App\Http\Controllers\Api;

use App\DTOs\DiscussionDTO;
use App\DTOs\SearchDTO;
use App\DTOs\InvitationDTO;
use App\DTOs\MessageDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDiscussionRequest;
use App\Http\Requests\MessageRequest;
use App\Http\Resources\DiscussionMessageResource;
use App\Http\Resources\DiscussionParticipantResource;
use App\Http\Resources\DiscussionPendingParticipantsResource;
use App\Http\Resources\DiscussionResource;
use App\Http\Resources\ParticipantProfileResource;
use App\Http\Resources\ParticipantResource;
use App\Http\Resources\UserAccountResource;
use App\Services\DiscussionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscussionController extends Controller
{
    public function createDiscussion(CreateDiscussionRequest $request)
    {
        try {
            DB::beginTransaction();

            $discussion = (new DiscussionService())->createDiscussion(
                DiscussionDTO::createFromRequest($request, true)
            );

            DB::commit();
            
            return response()->json([
                'message' => 'successful',
                'discussion' => new DiscussionResource($discussion),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    
    public function updateDiscussion(Request $request)
    {
        try {
            DB::beginTransaction();

            $discussion = (new DiscussionService())->updateDiscussion(
                DiscussionDTO::createFromRequest($request, true)
            );

            DB::commit();
            
            return response()->json([
                'message' => 'successful',
                'discussion' => new DiscussionResource($discussion)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    
    public function deleteDiscussion(Request $request)
    {
        try {
            DB::beginTransaction();

            (new DiscussionService())->deleteDiscussion(
                DiscussionDTO::createFromRequest($request, true)
            );

            DB::commit();

            return response()->json([
                'message' => 'successful',
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getDiscussions()
    {
        
    }

    public function getMessages(Request $request)
    {
        try {
            $messages = (new DiscussionService())->getMessages(
                DiscussionDTO::new()->addData(
                    discussionId: $request->discussionId,
                    state: $request->state,
                    userId: $request->user()?->id,
                )
            );

            return DiscussionMessageResource::collection($messages);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function sendMessage(MessageRequest $request, $discussionId)
    {
        try {
            DB::beginTransaction();
            
            $message = (new DiscussionService())->sendMessage(
                MessageDTO::createFromRequest($request)
                    ->addData(
                        item: 'discussion',
                        itemId: $discussionId,
                        state: 'sent',
                    )
                    ->addFiles(
                        $request->file('files')
                    )
            );

            DB::commit();
            
            return response()->json([
                'message' => 'successful',
                'discussionMessage' => $message ? new DiscussionMessageResource($message) : null
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return response()->json([
                'message' => 'unsuccessful, something happened.'
            ], 422);
        }
    }
    
    public function deleteMessage(Request $request)
    {
        try {
            DB::beginTransaction();

            (new DiscussionService)->deleteMessage(
                MessageDTO::createFromRequest($request)
            );
            
            DB::commit();
            
            return response()->json([
                'message' => 'successful',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function contributionResponse(Request $request)
    {
        try {
            DB::beginTransaction();

            (new DiscussionService())->contributionResponse(
                MessageDTO::createFromRequest($request)
            );

            DB::commit();
            
            return response()->json([
                'message' => 'successful',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    
    public function invitationResponse(Request $request)
    {
        try {
            DB::beginTransaction();

            $participant = (new DiscussionService())->invitationResponse(
                InvitationDTO::createFromRequest($request)
            );
            
            DB::commit();

            return response()->json([
                'message' => 'successful',
                'participant' => $participant ? 
                    new DiscussionParticipantResource($participant) : $participant
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    
    public function joinResponse(Request $request)
    {
        try {
            DB::beginTransaction();
            $participant = (new DiscussionService())->joinResponse(
                InvitationDTO::createFromRequest($request)
            );
            
            DB::commit();
            return response()->json([
                'message' => 'successful',
                'participant' => $participant ? 
                    new DiscussionParticipantResource($participant) : null
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function joinDiscussion(Request $request,$discussionId)
    {
        try {
            DB::beginTransaction();

            $participant = (new DiscussionService())->joinDiscussion(
                InvitationDTO::createFromRequest($request)
            );

            DB::commit();

            return response()->json([
                'message' => 'successful',
                'participant' => $participant ?
                    new DiscussionParticipantResource($participant) : null,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function discussionSearch(Request $request)
    {
        try {
            $accounts = (new DiscussionService())->discussionSearch(
                SearchDTO::createFromRequest($request)
            );

            return UserAccountResource::collection($accounts);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function inviteParticipant(Request $request)
    {
        try {
            DB::beginTransaction();

            $account = (new DiscussionService())->inviteParticipant(
                InvitationDTO::createFromRequest($request)
            );

            DB::commit();

            return response()->json([
                'message' => 'successful',
                'pendingParticipant' => $account ? 
                    new DiscussionPendingParticipantsResource($account) : null
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function getParticipants($discussionId)
    {
        $profiles = (new DiscussionService)->getParticipants(
            DiscussionDTO::new()->addData(discussionId: $discussionId)
        );

        return ParticipantProfileResource::collection($profiles);
    }

    public function updateDiscussionParticipant(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $participant = (new DiscussionService())->updateDiscussionParticipant(
                DiscussionDTO::createFromRequest($request, true)
            );

            DB::commit();

            return response()->json([
                'message' => 'successful',
                'participant' => new ParticipantResource($participant),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function deleteDiscussionParticipant(Request $request)
    {
        try {
            DB::beginTransaction();
            
            (new DiscussionService())->deleteDiscussionParticipant(
                DiscussionDTO::createFromRequest($request, true)
            );
            
            DB::commit();

            return response()->json([
                'message' => 'successful',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
