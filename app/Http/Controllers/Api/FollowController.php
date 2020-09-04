<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FollowRequestResource;
use App\Http\Resources\FollowResource;
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

class FollowController extends Controller
{
    //

    public function followRequests()
    {
        $requests = YourEduRequest::where('requestable_type','App\YourEdu\Follow')
            ->where('state','PENDING')
            ->whereHasMorph('requestto','*',function(Builder $builder){
                $builder->where('user_id',auth()->id());
            })->paginate(10);

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
            ]);
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
            $follow = $mainAccount->follows()->create([
                'user_id' => auth()->id(),
                'followed_user_id' => $mainAccount->user_id
            ]);

            if ($follow) {
                $follow->followedby()->associate($followerAccount);
                $follow->save();

                //ensure that requests will only be sent when there is no pending request from a
                //any of the followers user accounts
                $requestCounter = YourEduRequest::where('requestable_type','App\YourEdu\Follow')
                    ->where('state','PENDING')
                    ->whereHasMorph('requestfrom','*',function(Builder $b){
                        $b->where('user_id',auth()->id());
                })->count();

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
                }

                DB::commit();
                return response()->json([
                    'message' => 'successful',
                    'follow' => new FollowResource($follow),
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

        if($followerAccount->user_id !== auth()->id()){
            return response()->json([
                'message' => 'unsuccessful. you do not own the account that wants to follow.'
            ],422);
        }

        if ($followerAccount) {
            $follow->followedby()->associate($followerAccount);
            $follow->user_id = auth()->id();
            $follow->save();

            $mainRequest->update([
                'state' => 'ACCEPTED'
            ]);
            return response()->json([
                'message' => 'unsuccessful. follow does not exist'
            ],422);
        } else {
            return response()->json([
                'message' => 'unsuccessful. follow does not exist'
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
            $mainFollow->delete();

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
}
