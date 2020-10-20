<?php

namespace App\Http\Controllers\Api;

use App\Events\NewFollow;
use App\Http\Controllers\Controller;
use App\Http\Resources\FollowerResource;
use App\Http\Resources\FollowingResource;
use App\Http\Resources\FollowRequestResource;
use App\Http\Resources\FollowResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\UserAccountResource;
use App\Notifications\FollowRequest;
use App\Services\FollowService;
use App\User;
use App\YourEdu\Facilitator;
use App\YourEdu\Follow;
use App\YourEdu\Learner;
use App\YourEdu\ParentModel;
use App\YourEdu\Professional;
use App\YourEdu\Request as YourEduRequest;
use App\YourEdu\School;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Debugbar;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Notification;

class FollowController extends Controller
{
    public function followRequests()
    {
        $requests = (new FollowService())->followRequests(auth()->id());

        return FollowRequestResource::collection($requests);
    }

    public function follow(Request $request, $account, $accountId)
    {
        try {
            DB::beginTransaction();
            $followArray = (new FollowService())->follow($account,$accountId,
                $request->account,$request->accountId,auth()->id());
            
            DB::commit();
            Notification::send($followArray['users'],
                new FollowRequest($followArray['followRequestInfo']));
            broadcast(new NewFollow(new FollowerResource($followArray['follow']),
                $followArray['userId'],'followed'))
                ->toOthers();
            return response()->json([
                'message' => 'successful',
                'follow' => new FollowResource($followArray['follow']),
                'following' => new FollowingResource($followArray['follow']),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function declineFollowRequest(Request $request)
    {
        try {
            DB::beginTransaction();
            $message = (new FollowService())->declineFollowRequest($request->account,
                $request->accountId, $request->myAccount, $request->myAccountId);
            DB::commit();
            return response()->json([
                'message' => $message,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function followBack(Request $request)
    {
        try {
            DB::beginTransaction();
            $followArray = (new FollowService())->followBack($request->account,
                $request->accountId,$request->myAccount,$request->myAccountId,
                auth()->id());
            DB::commit();
            // Notification::send($followArray['users'],
            //     new FollowRequest($followArray['follow']->id));
            broadcast(new NewFollow(new FollowerResource($followArray['follow']),
                $request->userId,'followed back'))
                ->toOthers();
            return response()->json([
                'message' => 'successful',
                'follow' => new FollowResource($followArray['follow']),
                'following' => new FollowingResource($followArray['follow']),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }       
    }

    public function unfollow($follow)
    {
        try {

            DB::beginTransaction();
            $followInfo = (new FollowService())->unfollow($follow,auth()->id());

            Notification::send($followInfo['users'],
                new FollowRequest($followInfo['unfollowRequestInfo']));
            DB::commit();
            return response()->json([
                'message' => "successful"
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            // return response()->json([
            //     'message' => "successful"
            // ],422);
        }
    }

    public function getFollowers()
    {
        $followers =(new FollowService())->getFollowers(auth()->id());

        return FollowerResource::collection($followers);
    }

    public function getFollowings()
    {
        $followings = (new FollowService())->getFollowings(auth()->id());

        return FollowingResource::collection($followings);
    }

    
}
