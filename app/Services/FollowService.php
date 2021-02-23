<?php

namespace App\Services;

use App\Exceptions\AccountNotFoundException;
use App\Exceptions\FollowException;
use App\Exceptions\RequestException;
use App\User;
use App\YourEdu\Follow;
use App\YourEdu\Request;
use App\YourEdu\School;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use \Debugbar;
use Illuminate\Database\Eloquent\Builder;

class FollowService
{
    public function getFollowers($id)
    {
        $followers = Follow::withAccounts()->with(['followedby.conversations'=>function(MorphToMany $query) use ($id){
            $query->whereIn('type',['PRIVATE','GROUP'])
                ->with(['conversationAccounts'=>function(HasMany $query) use ($id){
                    $query->where('user_id',$id);
                }]);
        }])
            ->where('followed_user_id', $id)
            ->whereNotNull('user_id')->get();

        return $followers;
    }

    public function getFollowings($id)
    {
        $followings = Follow::withAccounts()->with(['followedby.conversations'=>function(MorphToMany $query) use ($id){
            $query->whereIn('type',['PRIVATE','GROUP'])
                ->with(['conversationAccounts'=>function(HasMany $query) use ($id){
                    $query->where('user_id',$id);
                }]);
        }])->where('user_id', $id)->get();

        return $followings;
    }

    public function unfollow($followId,$userId)
    {
        //user_id of follow belongs to follower, meaning follow belongs to follower 
        $mainFollow = getYourEduModel('follow',$followId);
        if ($mainFollow) {
            if ($mainFollow->user_id !== $userId) {
                throw new FollowException("you cannot unfollow a follow you do not own");
            }
        } else {
            throw new AccountNotFoundException("follow not found");
        }

        $request = $mainFollow->request;
        if (!is_null($request) && $request->state === 'PENDING') {
            $request->delete();
        }

        $mainAccount = $mainFollow->followedby;
        $account = class_basename_lower($mainFollow->followedby_type);
        $accountId = $mainFollow->followedby_id;
        $unfollowRequestInfo = [
            'followId' => $mainFollow->id,
            'account' => $account,
            'accountId' => $accountId,
        ];
        $mainFollow->delete();

        $users = null;
        if ($account === 'school') {
            $users = User::whereIn('id',$mainAccount->admins->pluck('user_id'))->get();
        } else {
            $users = User::find($mainAccount->user_id);
        }
        
        return [
            'users' => $users,
            'unfollowRequestInfo' => $unfollowRequestInfo,
        ];
    }

    public function followBack($account,$accountId,$myAccount, $myAccountId,$id)
    {
        $requestData = $this->getFollowRequest($account, $accountId, $myAccount, $myAccountId);
        
        $follow = $requestData['request']->requestable;
        if(($requestData['requestto']->user_id && 
            $requestData['requestto']->user_id !== $id) ||
            ($requestData['requestto']->owner_id && 
            $requestData['requestto']->owner_id !== $id)){
            throw new FollowException("unsuccessful. you do not own the account that wants to follow");
        }
        $follow->followedby()->associate($requestData['requestto']);
        $follow->user_id = auth()->id();
        $follow->save();

        $users = null;
        if ($account === 'school') {
            $users = User::whereIn('id',$requestData['requestfrom']->admins->pluck('user_id'))->get();
        } else {
            $users = User::find($requestData['requestfrom']->user_id);
        }
        $requestData['request']->update([
            'state' => 'ACCEPTED'
        ]);
        return [
            'follow' => $follow->load(['followedby.profile','followable.profile']),
            'account' => $requestData['requestto'],
            'users' => $users
        ];        
    }

    private function getFollowRequest($account, $accountId, $myAccount, $myAccountId)
    {
        $requestfrom = getYourEduModel($account, $accountId);
        if (is_null($requestfrom)) {
            throw new AccountNotFoundException("{$account} with id {$accountId} not found");
        }
        $requestto = getYourEduModel($myAccount, $myAccountId);
        if (is_null($requestto)) {
            throw new AccountNotFoundException("{$myAccount} with id {$myAccountId} not found");
        }
        $request = Request::where('requestfrom_type',get_class($requestfrom))
            ->where('requestfrom_id', $requestfrom->id)
            ->where('requestto_type',get_class($requestto))
            ->where('requestto_id', $requestto->id)
            ->where('state','PENDING')
            ->where('requestable_type','App\YourEdu\Follow')->latest()->first();
        
        if (is_null($request)) {
            throw new AccountNotFoundException("follow request was not found");
        }

        return [
            'request' => $request,
            'requestto' => $requestto,
            'requestfrom' => $requestfrom,
        ];
    }

    public function declineFollowRequest($account, $accountId, $myAccount, $myAccountId)
    {
        $requestArray = $this->getFollowRequest($account, $accountId, $myAccount, $myAccountId);

        $requestArray['request']->update([
            'state' => 'DECLINED'
        ]);
        return 'successful';
    }

    public function follow($account,$accountId,$myAccount,$myAccountId,$id)
    {
        //check if there is a request with the a follow
        $mainAccount = getYourEduModel($account, $accountId);
        if (!$mainAccount) {
            throw new AccountNotFoundException("unsuccessful. the account you are trying to follow does not exist");
        }

        $followerAccount = getYourEduModel($myAccount, $myAccountId);
        if (!$followerAccount) {
            throw new AccountNotFoundException("unsuccessful. your account does not exist");
        } 
        if ($followerAccount->user_id === $mainAccount->user_id) {
            throw new FollowException("unsuccessful. you cannot follow one of your own accounts");
        }
        if ($mainAccount->follows()->where('user_id',$id)->first()) {
            throw new FollowException("unsuccessful. you already follow this account");
        }

        $followedUserId = $mainAccount->user_id;
        if ($account === 'school') {
            $followedUserId = $mainAccount->owner_id;
        }
        $follow = $mainAccount->follows()->create([
            'user_id' => $id,
            'followed_user_id' => $followedUserId
        ]);
        if (is_null($follow)) {
            throw new FollowException("unsuccessful. follow was not created");
        }

        Debugbar::info("followedUserId {$followedUserId}");
        $follow->followedby()->associate($followerAccount);
        $follow->save();

        //ensure that requests will only be sent when there is no pending request from a
        //any of the followers user accounts
        $requestCounter = Request::where('requestable_type','App\YourEdu\Follow')
            ->where('state','PENDING')
            ->where('requestto_type',get_class($mainAccount))
            ->where('requestto_id',$mainAccount->id)
            ->whereHasMorph('requestfrom','*',function(Builder $builder,$type) use ($id){
                if ($type === 'App\YourEdu\School') {
                    $builder->whereIn('owner_id', $id);
                } else {
                    $builder->where('user_id',$id);
                }
            })
            ->count();
        
        Debugbar::info("requestCounter {$requestCounter}");
        if (!$requestCounter) {
            $newFollow = $followerAccount->follows()->create([
                'followed_user_id' => $id
            ]); //create an follow where this is being followed
            $followRequest = Request::create([
                'state' => 'PENDING'
            ]);
            $followRequest->requestFrom()->associate($followerAccount);
            $followRequest->requestto()->associate($mainAccount);
            $followRequest->requestable()->associate($newFollow);
            $followRequest->save();

            $followRequestInfo = [
                'requestId' => $followRequest->id,
                'account' => $myAccount,
                'accountId' => $myAccountId,
            ];
            $users = null;
            if ($account === 'school') {
                $users = User::whereIn('id',$mainAccount->admins->pluck('user_id'))->get();
            } else {
                $users = User::find($mainAccount->user_id);
            }

            return [
                'users' => $users,
                'followRequestInfo' => $followRequestInfo,
                'follow' => $follow->load(['followable.profile','followedby.profile']),
                'account' => $followerAccount,
                'userId'=> $followedUserId
            ];
        } else {
            throw new FollowException("unsuccessful. you have a pending request to this account.");
        }
    }

    public function followRequests($id)
    {
        $schools = School::whereHas('admins', function(Builder $query) use ($id){
            $query->where('user_id',$id);
        })->get();
        $schoolOwnerIds = [];
        foreach ($schools as $school) {
            $schoolOwnerIds[] = $school->owner_id;
        }

        Debugbar::info('schoolOwnerIds');
        Debugbar::info($schoolOwnerIds);
        $requests = Request::where('requestable_type','App\YourEdu\Follow')
            ->where('state','PENDING')
            ->whereHasMorph('requestto','*',function(Builder $builder,$type) use ($id,$schoolOwnerIds) {
                if ($type === 'App\YourEdu\School') {
                    $builder->whereIn('owner_id', $schoolOwnerIds);
                } else {
                    $builder->where('user_id',$id);
                }
            })->latest()->paginate(10);

        return $requests;
        
    }
}