<?php

namespace App\Services;

use App\Exceptions\AccountNotFoundException;
use App\Exceptions\DiscussionException;
use App\Notifications\DiscussionRequestNotification;
use App\YourEdu\Profile;
use App\YourEdu\Request;
use Illuminate\Support\Str;
use \Debugbar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DiscussionService
{
    public function createDiscussion($raiserType,$raiserId,$title,$preamble,
        $restricted,$type,$allowed, $files,$attachments)
    {
        $raisedby = getAccountObject($raiserType, $raiserId);

        if (is_null($raisedby)) {
            throw new AccountNotFoundException("{$raiserType} was not found by id {$raiserId}");
        }

        $discussion = $raisedby->discussions()->create([
            'title' => $title,
            'preamble' => $preamble,
            'restricted' => (bool)$restricted,  
            'type' => Str::upper($type),
            'allowed' => Str::upper($allowed),
        ]);

        if (is_null($discussion)) {
            throw new DiscussionException("Discussion creation failed.");
        }

        if (!is_null($files)) {
            foreach ($files as $file) {
                $fileDetails = getFileDetails($file);
                accountCreateFile($raisedby,$fileDetails,$discussion);
            }
        }
            
        if (!is_null($attachments)) {
            foreach ($attachments as $attachment) {
                $attach = getAccountObject($attachment->attachable, $attachment->attachableId);
                (new AttachmentService())->attach($raisedby,$discussion,$attach);
            }
        }

        return $discussion->load(['likes','messages','comments','flags','beenSaved',
        'attachments','participants','raisedby.profile']);
    }

    public function getMessages($discussionId, $type)
    {
        $discussion = getAccountObject('discussion', $discussionId);

        if (is_null($discussion)) {
            throw new DiscussionException("discussion with id {$discussionId} not found.");
        }

        $messages = [];
        if ($discussion->restricted && $type !== 'all') {
            $messages = $this->getMessagesByState($discussion,Str::upper($type));
        } else {
            $messages = $this->getMessagesByState($discussion,'ACCEPTED');
        }

        return $messages->sortByDesc('updated_at');
    }

    private function getMessagesByState($discussion, $state)
    {
        $messages = $discussion->messages()->where('state', $state)
            ->orderBy('updated_at','desc')->get();

        return $messages;
    }

    private function canUpdate($discussion, $id)
    {        
        if ($discussion->raisedby->user_id !== $id && 
            is_null($discussion->participants()->where('user_id', $id)
            ->where('state', 'ADMIN')->first())) {
            throw new DiscussionException('you are not authorized to update this discussion');
        }
    }
    
    public function updateDiscussion($discussionId, $id,$account,$accountId,$title,$restricted,
        $allowed,$type,$preamble,$files,$deletableFiles)
    {
        $adminAccount = getAccountObject($account, $accountId);
        if (is_null($adminAccount)) {
            throw new AccountNotFoundException("{$account} with id {$accountId} not found");
        }
        
        $discussion = getAccountObject('discussion', $discussionId);
        if (is_null($discussion)) {
            throw new AccountNotFoundException("discussion with id {$discussionId} not found");
        }

        $this->canUpdate($discussion, $id);

        $discussion->update([
            'title' => $title,
            'type' => Str::upper($type),
            'preamble' => $preamble,
            'allowed' => Str::upper($allowed),
            'restricted' => (bool)$restricted,
        ]);

        if (is_array($files)) {
            
            foreach ($files as $file) {
                $fileDetails = getFileDetails($file);
                accountCreateFile($adminAccount,$fileDetails,$discussion);
            }
        }
        foreach ($deletableFiles as $file) {
            deleteYourEduFile($file->id,$file->type);
        }

        return $discussion->load(['likes','messages','comments','flags','beenSaved',
            'attachments','participants','raisedby.profile']);
    }
    
    public function sendMessage($discussionId,$account, $accountId, $userId,$message,
        $state,$file)
    {
        $discussion = getAccountObject('discussion', $discussionId);
        if (is_null($discussion)) {
            throw new AccountNotFoundException('unsuccessful, discussion does not exist');
        }

        $participant = null;
        if (!is_null($userId)) {

            $participant = $discussion->participants()->where('user_id', $userId)->first();
            $account = getAccountString($participant->accountable_type);
            $accountId = $participant->accountable_id;
        } else{
            $participantAccount = getAccountObject($account, $accountId);
            if (is_null($participantAccount)) {
                throw new AccountNotFoundException("unsuccessful, {$account} does not exist");
            }
            $participant = $discussion->participants()
                ->whereHasMorph('accountable','*',function(MorphTo $query,$type) use ($participantAccount){
                    if ($type === 'App\YourEdu\School') {
                        $query->where('owner_id',$participantAccount->owner_id);
                    } else {
                        $query->where('user_id',$participantAccount->user_id);
                    }
                })
                ->first();
        }

        $messageData = (new MessageService())->createMessage('discussion',$discussionId,
            $account,$accountId,auth()->id(),$message,$state,$file);

        $request = null;
        if ($discussion->restricted) {
            $request = new Request();
            $request->state = 'PENDING';
            $request->requestto()->associate($discussion->raisedby);
            $request->requestable()->associate($messageData['message']);
            $request->requestfrom()->associate($participant->accountable);
            $request->save();
        }
        return [
            'message' => $messageData['message'],
            'request' => $request,
            'users' => $discussion->raisedby->user,
            'discussionRestriction' => $discussion->restricted
        ];
    }

    public function contributionResponse($messageId, $userId, $action)
    {
        $message = getAccountObject('message', $messageId);
        if (is_null($message)) {
            throw new AccountNotFoundException("message not found with id {$messageId}");
        }

        $discussion = getAccountObject('discussion',$message->messageable_id);
        if (is_null($discussion)) {
            throw new AccountNotFoundException("discussion not found with id {$message->messageable_id}");
        }

        $this->canUpdate($discussion,$userId);

        $request = Request::where('requestable_type','App\YourEdu\Message')
            ->where('requestable_id',$message->id)
            ->where('state','PENDING')
            ->first();
        if (!is_null($request)) {
            if ($action === 'accepted') {
                $request->state = 'ACCEPTED';
            } else {
                $request->state = 'DECLINED';
            }
            $request->save();
        }

        $message->state = Str::upper($action);
        $message->save();

        return $message->load(['images','videos','audios','files','fromable.profile']);
    }

    private function getDiscussionAdminIds($discussion)
    {
        $ids = $discussion->participants->pluck('user_id');
        $ids->push($discussion->raisedby->user_id ? $discussion->raisedby->user_id :
            $discussion->raisedby->owner_id);

        return $ids;
    }
    
    public function updateParticipantState($discussionId,$participantId,$action,$id)
    {
        $discussion = getAccountObject('discussion',$discussionId);
        if (is_null($discussion)) {
            throw new AccountNotFoundException("discussion not found with id {$discussionId}");
        }

        $this->canUpdate($discussion,$id);

        $participant = $discussion->participants()->where('id',$participantId)->first();
        if (is_null($participant)) {
            throw new DiscussionException("participant with id {$participantId} does not belong to discussion not found with id {$discussionId}");
        }

        $participant->state = Str::upper($action);
        $participant->save();

        return [
            'participant' => $participant,
            'title' => $discussion->title,
        ];
    }
    
    public function deleteDiscussionParticipant($discussionId,$participantId,$id,$userId)
    {
        $discussion = getAccountObject('discussion',$discussionId);
        if (is_null($discussion)) {
            throw new AccountNotFoundException("discussion not found with id {$discussionId}");
        }

        if ($id != $userId) {
            $this->canUpdate($discussion,$id);
        }

        $participant = $discussion->participants()->where('id',$participantId)->first();
        if (is_null($participant)) {
            throw new DiscussionException("participant with id {$participantId} does not belong to discussion not found with id {$discussionId}");
        }

        $theParticipant = $participant;
        $participant->delete();

        return [
            'participant' => $theParticipant,
            'title' => $discussion->title,
            'userIds' => $this->getDiscussionAdminIds($discussion),
        ];
    }

    private function getDiscussionAdminsId($discussion)
    {
        $array = $discussion->participants()->where('state','ADMIN')
            ->get()->pluck('user_id');
        $array[] = $discussion->raisedby->user_id;

        return $array;
    }

    public function discussionSearch($discussionId,$search,$searchType,$user)
    {
        $discussion = getAccountObject('discussion',$discussionId);
        if (is_null($discussion)) {
            throw new AccountNotFoundException("discussion not found with id {$discussionId}");
        }
        $discussion->load('raisedby');
        $adminsUserIds = $this->getDiscussionAdminsId($discussion);

        $withoutUserIds = $discussion->participants->pluck('user_id'); 
        $withoutUserIds[] = $discussion->raisedby->user_id;
        Debugbar::info($withoutUserIds);

        $theOther = $discussion->requests()
        ->whereHasMorph('requestfrom','*',function(Builder $query,$type) use ($adminsUserIds){
            if ($type === 'App\YourEdu\School') {
                $query->whereIn('owner_id',$adminsUserIds);
            } else {
                $query->whereIn('user_id',$adminsUserIds);
            }                
        })->where('state','PENDING')->get()->pluck('requestto')->map(function($item,$index){
            if ($item['user_id']) {
                return $item['user_id'];
            }
            $item['owner_id'];
        });
        Debugbar::info($theOther);

        $withoutUserIds = $withoutUserIds->merge($theOther);         
        Debugbar::info($withoutUserIds);

        $searchClass = null;
        if ($searchType === 'profiles') {
            $searchClass = '*';
        } else {
            $searchClass = getAccountClass($searchType);
        }

        if ($user->learner && count($user->learner->parents)) {
            $parentsLearnerUserIds = $user->learner->parents->pluck('user_id');
        }
        $parentsLearnerUserIds[] = $user->id;

        $accounts = Profile::where(function($query) use ($search,$searchClass){
                $query->where('name','like',"%{$search}%")
                ->orWhereHasMorph('profileable',$searchClass,function(Builder $query) use ($search){
                    $query->where('name','like',"%{$search}%");
                });
            })
            ->whereNotIn('user_id',$withoutUserIds)
            ->hasNoFlags($parentsLearnerUserIds)
            ->get()->pluck('profileable');

        return $accounts;
    }

    public function inviteParticipant($discussionId,$account,$accountId,$id)
    {
        $discussion = getAccountObject('discussion',$discussionId);
        if (is_null($discussion)) {
            throw new AccountNotFoundException("discussion not found with id {$discussionId}");
        }

        $invitedAccount = getAccountObject($account,$accountId);
        if (is_null($invitedAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }

        $adminAccount = null;
        if ($discussion->raisedby->user_id &&
            $discussion->raisedby->user_id === $id) {
            $adminAccount = $discussion->raisedby;
        } else {
            $adminAccount = $discussion->participants()->where('user_id',$id)
                ->where('state',"ADMIN")->first();
        }
        if (is_null($adminAccount)) {
            throw new AccountNotFoundException("admin account was not found");
        }

        $request = $adminAccount->requestsSent()->create();
        $request->requestable()->associate($discussion);
        $request->requestto()->associate($invitedAccount);
        $request->state = 'PENDING';
        $request->save();

        return [
            'request' => $request,
            'admin' => $adminAccount,
            'title' => $discussion->title,
            'user' => !is_null($invitedAccount->user_id) ? $invitedAccount->user : 
                $invitedAccount->owner,
        ];
    }
    
    private function createParticipant($discussion,$account){
        $participant = $discussion->participants()->create([
            'user_id' => $account->user_id ? $account->user_id : $account->owner_id,
            'state' => 'ACTIVE'
        ]);

        $participant->accountable()->associate($account);
        $participant->save();

        return $participant;
    }

    private function joinInvitationResponseData($requestId,$discussionId,$account,$accountId)
    {
        $request = getAccountObject('request',$requestId);
        if (is_null($request)) {
            throw new AccountNotFoundException("request not found with id {$requestId}");
        }
        if ($request->requestable_type !== 'App\YourEdu\Discussion') {
            throw new DiscussionException("request with id {$requestId} not a request to join discussion");
        }

        $mainAccount = getAccountObject($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }
        if ($request->requestfrom->user_id !== $mainAccount->user_id && 
            $request->requestfrom->owner_id !== $mainAccount->owner_id) {
            throw new DiscussionException("request with id {$requestId} not from this account");
        }

        $discussion = getAccountObject('discussion',$discussionId);
        if (is_null($discussion)) {
            throw new AccountNotFoundException("discussion not found with id {$discussionId}");
        }

        return [
            'discussion' => $discussion,
            'requestfrom' => $mainAccount, //this is to help me use it in more than one function
            'requestto' => $request->requestto,
            'request' => $request,
        ];
    }

    public function joinResponse($account,$accountId,$requestId,$discussionId,$response)
    {
        $data = $this->joinInvitationResponseData($requestId,$discussionId,$account,$accountId);

        $data['request']->state = Str::upper($response);
        $data['request']->save();
        if ($response === 'declined') {
            return [
                'type' => 'request',
                'title' => $data['discussion']->title,
                'requestfrom' => $data['requestfrom'],
                'user'=> $data['requestfrom']->user ? $data['requestfrom']->user : $data['requestfrom']->owner
            ];
        }

        $participant = $this->createParticipant($data['discussion'], $data['requestfrom']);
        
        return [
            'type' => 'participant',
            'title' => $data['discussion']->title,
            'requestfrom' => $data['requestfrom'],
            'participant' => $participant,
            'user'=> $data['requestfrom']->user ? $data['requestfrom']->user : $data['requestfrom']->owner
        ];
    }    
    
    public function invitationResponse($account,$accountId,$requestId,$discussionId,$response)
    {
        $data = $this->joinInvitationResponseData($requestId,$discussionId,$account,$accountId,false);

        $data['request']->state = Str::upper($response);
        $data['request']->save();
        if ($response === 'declined') {
            return [
                'title' => $data['discussion']->title,
                'requestto' => $data['requestto'],
                'user'=> $data['request']->requestfrom->user ? $data['request']->requestfrom->user :
                    $data['request']->requestfrom->owner
            ];
        }

        $participant = $this->createParticipant($data['discussion'], $data['requestto']);
        
        return [
            'title' => $data['discussion']->title,
            'requestto' => $data['requestto'],
            'participant' => $participant,
            'user'=> $data['request']->requestfrom->user ? $data['request']->requestfrom->user :
                $data['request']->requestfrom->owner
        ];
    }
    
    public function deleteDiscussion($discussionId, $id)
    {
        $discussion = getAccountObject('discussion', $discussionId);
        if (is_null($discussion)) {
            throw new AccountNotFoundException("discussion with id {$discussionId} no found");
        }

        $this->canUpdate($discussion, $id);
        $discussionInfo = [
            'discussionId' => $discussionId,
            'account' => getAccountString($discussion->raisedby_type),
            'accountId' => $discussion->raisedby_id,
        ];
        $discussion->delete();

        return $discussionInfo;
    }

    public function joinDiscussion($discussionId,$account,$accountId,$userId)
    {
        $discussion = getAccountObject('discussion', $discussionId);

        if (is_null($discussion)) {
            throw new AccountNotFoundException("discussion with id {$discussionId} not found");
        }

        $joiningAccount = getAccountObject($account, $accountId);

        if (is_null($joiningAccount)) {
            throw new AccountNotFoundException("{$account} with id {$accountId} not found");
        }

        //if private, make request and send notification
        //else make participant
        if ($discussion->type === 'PRIVATE') {
            $request = $joiningAccount->requestsSent()->create();
            $request->requestto()->associate($discussion->raisedby);
            $request->requestable()->associate($discussion);
            $request->state = 'PENDING';
            $request->save();
            $discussion->raisedby->user->notifyNow(new DiscussionRequestNotification([
                'requestId' => $request->id
            ]));
            return [
                'type' => 'pendingParticipant',
                'requestfrom' => $joiningAccount
            ];
        } else {
            $participant = $this->createParticipant($discussion,$joiningAccount);
            return [
                'type' => 'participant',
                'participant' => $participant
            ];
        }
    }
}

?>