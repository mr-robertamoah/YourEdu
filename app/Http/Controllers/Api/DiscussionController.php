<?php

namespace App\Http\Controllers\Api;

use App\Events\DeleteDiscussion;
use App\Events\DeleteDiscussionMessage;
use App\Events\NewDiscussion;
use App\Events\NewDiscussionMessage;
use App\Events\NewDiscussionMessageResponse;
use App\Events\NewDiscussionParticipant;
use App\Events\NewDiscussionPendingParticipant;
use App\Events\RemoveDiscussionParticipant;
use App\Events\RemoveDiscussionPendingParticipant;
use App\Events\UpdatedDiscussionParticipant;
use App\Events\UpdateDisucssion;
use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Http\Resources\DiscussionMessageResource;
use App\Http\Resources\DiscussionParticipantResource;
use App\Http\Resources\DiscussionPendingParticipantsResource;
use App\Http\Resources\DiscussionResource;
use App\Http\Resources\ParticipantResource;
use App\Http\Resources\UserAccountResource;
use App\Jobs\RemoveDiscussionParticipantNotificationJob;
use App\Jobs\UpdateParticipantStateNotificationJob;
use App\Notifications\DiscussionInvitationNotification;
use App\Notifications\DiscussionInvitationResponseNotification;
use App\Notifications\DiscussionJoinResponseNotification;
use App\Notifications\NewDiscussionMessageNotification;
use App\Services\DiscussionService;
use App\Services\MessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use \Debugbar;

class DiscussionController extends Controller
{
    public function createDiscussion(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'preamble' => 'nullable|string',
            'type' => 'required|in:public,private',
            'allowed' => 'required|in:all,learners,parents,facilitators,professionals,schools',
        ]);

        try {
            DB::beginTransaction();
            $discussion = (new DiscussionService())->createDiscussion(
                $request->account,$request->accountId,$request->title,$request->preamble,
                json_decode($request->restricted),$request->type,$request->allowed,
                $request->file('file'),json_decode($request->attachments)
            );

            DB::commit();
            $discussionResource = new DiscussionResource($discussion);
            broadcast(new NewDiscussion($discussionResource))->toOthers();
            return response()->json([
                'message' => 'successful',
                'discussion' => $discussionResource,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    
    public function updateDiscussion(Request $request, $discussionId)
    {
        try {
            DB::beginTransaction();
            $discussion = (new DiscussionService())->updateDiscussion($discussionId,auth()->id(),
                $request->account,$request->accountId,$request->title,
                json_decode($request->restricted),$request->allowed,$request->type,
                $request->preamble,$request->file('files'),json_decode($request->deletedFiles));

            DB::commit();
            $discussionResource = new DiscussionResource($discussion);
            broadcast(new UpdateDisucssion($discussionResource))->toOthers();
            return response()->json([
                'message' => 'successful',
                'discussion' => $discussionResource
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    
    public function deleteDiscussion(Request $request, $discussionId)
    {
        try {
            DB::beginTransaction();
            $discussionInfo = (new DiscussionService())
                ->deleteDiscussion($discussionId,auth()->id());

            DB::commit();
            broadcast(new DeleteDiscussion($discussionInfo))->toOthers();
            return response()->json([
                'message' => 'successful',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function getDiscussions()
    {
        
    }

    public function getMessages(Request $request,$discussionId)
    {
        try {
            $messages = (new DiscussionService())->getMessages($discussionId, $request->type);

            return DiscussionMessageResource::collection(paginate($messages,5));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function sendMessage(MessageRequest $request, $discussionId)
    {
        try {
            DB::beginTransaction();

            $messageInfo = (new DiscussionService())->sendMessage($discussionId,
                $request->account,$request->accountId,$request->userId,$request->message,
                $request->state,$request->file('file'));

            DB::commit();
            if ($messageInfo['discussionRestriction']) {
                Notification::sendNow($messageInfo['users'],
                    new NewDiscussionMessageNotification($messageInfo['request']->id));
            }
            broadcast(new NewDiscussionMessage(
                new DiscussionMessageResource($messageInfo['message']),$discussionId))->toOthers();
            return response()->json([
                'message' => 'successful',
                'discussionMessage' =>  new DiscussionMessageResource($messageInfo['message'])
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return response()->json([
                'message' => 'unsuccessful, something happened.'
            ], 422);
        }
    }

    public function contributionResponse(Request $request)
    {
        try {
            DB::beginTransaction();
            $message = (new DiscussionService())->contributionResponse($request->messageId,
                auth()->id(),$request->action);
            DB::commit();
            $messageResource = new DiscussionMessageResource($message);
            broadcast(new NewDiscussionMessageResponse(
                $messageResource,$request->userId))
                ->toOthers();
            return response()->json([
                'message' => 'successful',
                'discussionMessage' => $messageResource,
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

            $message = (new MessageService())
                    ->deleteMessage(auth()->id(),$request->messageId,$request->action);
            
            DB::commit();
            if ($request->action === 'delete') {
                
                broadcast(new DeleteDiscussionMessage($message['itemId'], 
                    $request->discussionId))->toOthers();
                return response()->json([
                    'message' => 'successful'
                ]);
            } else if ($request->action === 'self') {
                return response()->json([
                    'message' => 'successful',
                    'discussionMessage' => new DiscussionMessageResource($message)
                ]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    
    public function invitationResponse(Request $request)
    {
        try {
            DB::beginTransaction();
            $responseData = (new DiscussionService())->invitationResponse($request->account,
                $request->accountId,$request->requestId,$request->discussionId,
                $request->action);
            Debugbar::info($responseData);
            if ($request->action === 'accepted') {
                broadcast(new NewDiscussionParticipant(
                    new DiscussionParticipantResource($responseData['participant']),
                    $request->discussionId));
            }
            Notification::sendNow($responseData['user'],
                new DiscussionInvitationResponseNotification($responseData['title'],
                    $request->action, $request->discussionId,
                    $responseData['requestto'])
            );
            
            DB::commit();
            return response()->json([
                'message' => 'successful'
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
            $responseData = (new DiscussionService())->joinResponse($request->account,
                $request->accountId,$request->requestId,$request->discussionId,
                $request->action);
            if ($request->action === 'accepted') {
                
                broadcast(new NewDiscussionParticipant(
                    new DiscussionParticipantResource($responseData['participant']),
                    $request->discussionId));
            }
            $responseData['user']
                ->notifyNow(new DiscussionJoinResponseNotification($responseData['title'],
                $request->action));
            broadcast(new RemoveDiscussionPendingParticipant(
                new DiscussionPendingParticipantsResource($responseData['requestfrom']),
                $request->discussionId));
            DB::commit();
            return response()->json([
                'message' => 'successful'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function joinDiscussion(Request $request,$discussionId)
    {
        try {
            $typeInfo = (new DiscussionService())->joinDiscussion($discussionId,
            $request->account, $request->accountId, auth()->id());

            if ($typeInfo['type'] === 'participant') {
                $participantResource = new DiscussionParticipantResource($typeInfo['participant']);
                broadcast(new NewDiscussionParticipant($participantResource,$discussionId))->toOthers();
                return response()->json([
                    'message' => 'successful',
                    'discussionParticipant' => $participantResource,
                ]);
            } else if ($typeInfo['type'] === 'pendingParticipant') {
                $pendingParticipantResource = new DiscussionPendingParticipantsResource($typeInfo['requestfrom']);
                broadcast(new NewDiscussionPendingParticipant(
                    $pendingParticipantResource,$discussionId))->toOthers();
                return response()->json([
                    'message' => 'successful',
                    'pendingParticipant' => $pendingParticipantResource,
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function discussionSearch(Request $request)
    {
        try {
            $accounts = (new DiscussionService())->discussionSearch($request->discussionId,
                $request->search,$request->searchType,auth()->user());

            return UserAccountResource::collection(paginate($accounts, 5));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function inviteParticipant(Request $request)
    {
        try {
            DB::beginTransaction();
            $requestData = (new DiscussionService())->inviteParticipant($request->discussionId,
                $request->account,$request->accountId,auth()->id());
            $requestData['user']->notifyNow(new DiscussionInvitationNotification(
                $requestData['request']->id,$requestData['admin']->name,
                $requestData['title'],new UserAccountResource($requestData['admin']),
                $request->discussionId
            ));
            DB::commit();
            return response()->json([
                'message' => 'successful'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function getParticipants($discussionId)
    {
        $discussion = getYourEduModel('discussion',$discussionId);

        $participants = $discussion->participants()
            ->latest()->with('accountable.profile')
            ->get();
        $participants->push($discussion->raisedby->load('profile'));

        return ParticipantResource::collection(paginate($participants,10));
    }

    public function updateParticipantState(Request $request)
    {
        try {
            DB::beginTransaction();
            $id = auth()->id();
            $participantData = (new DiscussionService())->updateParticipantState($request->discussionId,
                $request->participantId,$request->action,$id);

            if ($request->userId != $id) {                
                UpdateParticipantStateNotificationJob::dispatchAfterResponse(
                    $request->userId,$request->action,$participantData['title'],
                    $participantData['participant']);
            }
            
            broadcast(new UpdatedDiscussionParticipant(new DiscussionParticipantResource($participantData['participant']),
                $request->discussionId));

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'discussionParticipant' => new ParticipantResource($participantData['participant']),
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
            $id = auth()->id();
            $participantData = (new DiscussionService())->deleteDiscussionParticipant($request->discussionId,
                $request->participantId,$id,$request->userId);

            Debugbar::info($participantData['userIds']);
            if ($request->userId != $id) {
                RemoveDiscussionParticipantNotificationJob::dispatchAfterResponse(
                    $request->userId,$participantData['title'],
                    $participantData['participant'],'one');
            } else {
                RemoveDiscussionParticipantNotificationJob::dispatchAfterResponse(
                    $participantData['userIds'],$participantData['title'],
                    $participantData['participant'],'many');
            }
            
            broadcast(new RemoveDiscussionParticipant($request->userId,
                $request->discussionId));

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
