<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FollowerResource;
use App\Http\Resources\FollowingResource;
use App\Http\Resources\FollowRequestResource;
use App\Http\Resources\FollowResource;
use App\Notifications\FollowRequest;
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
    //

    public function followRequests()
    {
        $schools = School::whereHas('admins', function(Builder $query){
            $query->where('user_id',auth()->id());
        })->get();
        $schoolOwnerIds = [];
        foreach ($schools as $school) {
            $schoolOwnerIds[] = $school->owner_id;
        }
        // $schoolOwnerIds[] = auth()->id();
        Debugbar::info('schoolOwnerIds');
        Debugbar::info($schoolOwnerIds);
        $requests = YourEduRequest::where('requestable_type','App\YourEdu\Follow')
            ->where('state','PENDING')
            ->whereHasMorph('requestto','*',function(Builder $builder,$type) use ($schoolOwnerIds) {
                if ($type === 'App\YourEdu\School') {
                    $builder->whereIn('owner_id', $schoolOwnerIds);
                } else {
                    $builder->where('user_id',auth()->id());
                }
            })->latest()->paginate(10);

        return FollowRequestResource::collection($requests);
        
    }

    public function follow(Request $request, $account, $accountId)
    {

        //check if there is a request with the a follow
        $followRequest = null;
        $mainAccount = null;
        $followerAccount = null;
        if ($account === 'learner') {
            $mainAccount = Learner::find($accountId);
        } else if ($account === 'facilitator') {
            $mainAccount = Facilitator::find($accountId);
        }if ($account === 'professional') {
            $mainAccount = Professional::find($accountId);
        }if ($account === 'parent') {
            $mainAccount = ParentModel::find($accountId);
        }if ($account === 'school') {
            $mainAccount = School::find($accountId);
        }

        if (!$mainAccount) {
            return response()->json([
                'message' => 'unsuccessful. the account you are trying to follow does not exist'
            ],422);
        }

        if ($request->account === 'learner') {
            $followerAccount = Learner::find($request->accountId);
        } else if ($request->account === 'facilitator') {
            $followerAccount = Facilitator::find($request->accountId);
        }if ($request->account === 'professional') {
            $followerAccount = Professional::find($request->accountId);
        }if ($request->account === 'parent') {
            $followerAccount = ParentModel::find($request->accountId);
        }if ($request->account === 'school') {
            $followerAccount = School::find($request->accountId);
        }

        if (!$followerAccount) {
            return response()->json([
                'message' => 'unsuccessful. your account does not exist'
            ], 422);
        }

        
        if ($followerAccount->user_id === $mainAccount->user_id) {
            return response()->json([
                'message' => 'unsuccessful. you cannot follow one of your own accounts'
            ], 422);
        }

        if ($mainAccount->follows()->where('user_id',auth()->id())->first()) {
            return response()->json([
                'message' => 'unsuccessful. you already follow this account.'
            ], 422);
        }

        try {
            DB::beginTransaction();
            $followedUserId = $mainAccount->user_id;
            if ($account === 'school') {
                $followedUserId = $mainAccount->owner_id;
            }
            $follow = $mainAccount->follows()->create([
                'user_id' => auth()->id(),
                'followed_user_id' => $followedUserId
            ]);

            Debugbar::info("followedUserId {$followedUserId}");
            if ($follow) {
                $follow->followedby()->associate($followerAccount);
                $follow->save();

                //ensure that requests will only be sent when there is no pending request from a
                //any of the followers user accounts
                $requestCounter = YourEduRequest::where('requestable_type','App\YourEdu\Follow')
                    ->where('state','PENDING')
                    ->where('requestto_type',get_class($mainAccount))
                    ->where('requestto_id',$mainAccount->id)
                    ->whereHasMorph('requestfrom','*',function(Builder $builder,$type) use ($schoolOwnerIds) {
                        if ($type === 'App\YourEdu\School') {
                            $builder->whereIn('owner_id', auth()->id());
                        } else {
                            $builder->where('user_id',auth()->id());
                        }
                    })
                    ->count();
                
                Debugbar::info("requestCounter {$requestCounter}");
                if (!$requestCounter) {
                    $newFollow = $followerAccount->follows()->create([
                        'followed_user_id' => auth()->id()
                    ]); //create an follow where this is being followed
                    $followRequest = YourEduRequest::create([
                        'state' => 'PENDING'
                    ]);
                    $followRequest->requestFrom()->associate($followerAccount);
                    $followRequest->requestto()->associate($mainAccount);
                    $followRequest->requestable()->associate($newFollow);
                    $followRequest->save();

                    $followRequestInfo = [
                        'requestId' => $followRequest->id,
                        'account' => $request->account,
                        'accountId' => $request->accountId,
                    ];
                    if ($account === 'school') {
                        $users = User::whereIn('id',$mainAccount->admins->pluck('user_id'))->get();
                        Notification::send($users,new FollowRequest($followRequestInfo));
                    } else {
                        $user = null;
                        $user = User::find($mainAccount->user_id);
                        $user->notify(new FollowRequest($followRequestInfo));
                    }
                }

                DB::commit();
                $follow = Follow::withAccounts()->find($follow->id);
                return response()->json([
                    'message' => 'successful',
                    'follow' => new FollowResource($follow),
                    'following' => new FollowingResource($follow),
                ]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function declineFollowRequest(Request $request, $requestId)
    {
        $mainRequest = YourEduRequest::find($requestId);

        if (is_null($mainRequest)) {
            return response()->json([
                'message' => 'unsuccessful. request does not exist'
            ],422);
        }

        if ($mainRequest->requestable_type !== 'App\YourEdu\Follow') {
            return response()->json([
                'message' => 'unsuccessful. this is not a follow request'
            ],422);
        }
        
        try {
            $mainRequest->update([
                'state' => 'DECLINED'
            ]);
            return response()->json([
                'message' => 'successful'
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function followBack(Request $request, $requestId)
    {
        $mainRequest = YourEduRequest::find($requestId);

        if (is_null($mainRequest)) {
            return response()->json([
                'message' => 'unsuccessful. request does not exist'
            ],422);
        }

        if ($mainRequest->requestable_type !== 'App\YourEdu\Follow') {
            return response()->json([
                'message' => 'unsuccessful. this is not a follow request'
            ],422);
        }

        $follow = $mainRequest->requestable;

        if (is_null($follow)) {
            $mainRequest->delete();

            return response()->json([
                'message' => 'unsuccessful. follow does not exist'
            ],422);
        }

        $followerAccount = null;
        if ($request->account === 'learner') {
            $followerAccount = Learner::find($request->accountId);
        } else if ($request->account === 'facilitator') {
            $followerAccount = Facilitator::find($request->accountId);
        }if ($request->account === 'professional') {
            $followerAccount = Professional::find($request->accountId);
        }if ($request->account === 'parent') {
            $followerAccount = ParentModel::find($request->accountId);
        }if ($request->account === 'school') {
            $followerAccount = School::find($request->accountId);
        }
        
        if ($followerAccount) {
            if($followerAccount->user_id !== auth()->id()){
                return response()->json([
                    'message' => 'unsuccessful. you do not own the account that wants to follow.'
                ],422);
            }
            $follow->followedby()->associate($followerAccount);
            $follow->user_id = auth()->id();
            $follow->save();

            $mainRequest->update([
                'state' => 'ACCEPTED'
            ]);
            $follow = Follow::withAccounts()->find($follow->id);
            return response()->json([
                'message' => 'successful',
                'follow' => new FollowResource($follow),
                'following' => new FollowingResource($follow),
            ],422);
        } else {
            return response()->json([
                'message' => 'unsuccessful. follower does not exist'
            ],422);
        }
        
    }

    public function unfollow($follow)
    {
        //user_id of follow belongs to follower, meaning follow belongs to follower 
        $mainFollow = Follow::find($follow);
        // dd($request->route('like'));
        if ($mainFollow) {
            if ($mainFollow->user_id !== auth()->id()) {
                return response()->json([
                    'message' => 'you cannot unfollow a follow you do not own.'
                ]);
            }
        } else {
            return response()->json([
                'message' => 'there is no such follow.'
            ]);
        }

        try {

            DB::beginTransaction();
            $request = $mainFollow->request;
            if (!is_null($request) && $request->state === 'PENDING') {
                $request->delete();
            }
            $mainAccount = $mainFollow->followedby;
            $account = getAccountString($mainFollow->followedby_type);
            $accountId = $mainFollow->followedby_id;
            $unfollowRequestInfo = [
                'followId' => $mainFollow->id,
                'account' => $account,
                'accountId' => $accountId,
            ];
            $mainFollow->delete();

            if ($account === 'school') {
                $users = User::whereIn('id',$mainAccount->admins->pluck('user_id'))->get();
                Notification::send($users,new FollowRequest($unfollowRequestInfo));
            } else {
                $user = null;
                $user = User::find($mainAccount->user_id);
                $user->notify(new FollowRequest($unfollowRequestInfo));
            }
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
        $followers = Follow::withAccounts()->with(['followedby.conversations'=>function(MorphToMany $query){
            $query->whereIn('type',['PRIVATE','GROUP'])
                ->with(['conversationAccounts'=>function(HasMany $query){
                    $query->where('user_id',auth()->id());
                }]);
        }])
            ->where('followed_user_id', auth()->id())
            ->whereNotNull('user_id')->get();

        // return $followers;
        return FollowerResource::collection($followers);
    }

    public function getFollowings()
    {
        $followings = Follow::withAccounts()->with(['followedby.conversations'=>function(MorphToMany $query){
            $query->whereIn('type',['PRIVATE','GROUP'])
                ->with(['conversationAccounts'=>function(HasMany $query){
                    $query->where('user_id',auth()->id());
                }]);
        }])->where('user_id', auth()->id())->get();

        return FollowingResource::collection($followings);
    }

    public function markFollowNotifications()
    {
        $user = User::find(auth()->id());

        if ($user) {
            $user->unreadNotifications()
                ->where('type','App\Notifications\FollowRequest')->markAsRead();
            return response()->json([
                'message' => 'successful'
            ]);
        } else {
            return response()->json([
                'message' => 'unsuccessful. user not found.'
            ], 422);
        }
    }

    public function followNotifications()
    {
        $user = User::find(auth()->id());

        if ($user) {
            $notifications = $user->unreadNotifications()
                ->where('type','App\Notifications\FollowRequest')->get();
            return response()->json([
                'message' => 'successful',
                'notifications' => $notifications
            ]);
        } else {
            return response()->json([
                'message' => 'unsuccessful. user not found.'
            ], 422);
        }
    }
}
