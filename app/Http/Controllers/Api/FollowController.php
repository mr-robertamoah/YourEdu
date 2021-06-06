<?php

namespace App\Http\Controllers\Api;

use App\DTOs\FollowDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\FollowerResource;
use App\Http\Resources\FollowingResource;
use App\Http\Resources\FollowRequestResource;
use App\Http\Resources\FollowResource;
use App\Services\FollowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Debugbar;

class FollowController extends Controller
{
    public function followRequests()
    {
        $requests = (new FollowService())->followRequests(auth()->id());

        return FollowRequestResource::collection($requests);
    }

    public function follow(Request $request)
    {
        try {
            DB::beginTransaction();

            $follow = (new FollowService())->follow(
                FollowDTO::createFromRequest($request)
            );
            
            DB::commit();
            
            return response()->json([
                'message' => 'successful',
                'follow' => new FollowResource($follow),
                'following' => new FollowingResource($follow),
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

            (new FollowService())->declineFollowRequest(
                FollowDTO::createFromRequest($request)
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

    public function followBack(Request $request)
    {
        try {
            DB::beginTransaction();

            $follow = (new FollowService())->followBack(
                FollowDTO::createFromRequest($request)
            );

            DB::commit();
            
            return response()->json([
                'message' => 'successful',
                'follow' => new FollowResource($follow),
                'following' => new FollowingResource($follow),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }       
    }

    public function unfollow(Request $request)
    {
        try {

            DB::beginTransaction();
            
            (new FollowService)->unfollow(
                FollowDTO::createFromRequest($request)
            );

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
