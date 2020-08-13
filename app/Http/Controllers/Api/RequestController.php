<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use App\YourEdu\Facilitator;
use App\YourEdu\Learner;
use App\YourEdu\ParentModel;
use App\YourEdu\Professional;
use App\YourEdu\Request as YourEduRequest;
use App\YourEdu\School;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RequestController extends Controller
{
    //

    public function getFollowRequests()
    {
        // $mergeFollowRequest = [];

        $user = User::find(auth()->id());

        try {
            if ($user) {
                if ($user->facilitator) {
                    $mergeRequest = $user->facilitator()->whereHas('requestsReceived',function(Builder $query){
                        $query->where('requestable_type','App\YourEdu\Follow')
                        ->where('state','PENDING');
                    })->with('requestsReceived')->get();
                }
                
                if ($user->learner) {
                    $mergeRequest->merge($user->learner()->whereHas('requestsReceived',function(Builder $query){
                        $query->where('requestable_type','App\YourEdu\Follow')
                        ->where('state','PENDING');
                    })->with('requestsReceived')->get());
                }
                
                if ($user->professionals) {
                    $mergeRequest->merge($user->professionals()->whereHas('requestsReceived',function(Builder $query){
                        $query->where('requestable_type','App\YourEdu\Follow')
                        ->where('state','PENDING');
                    })->with('requestsReceived')->get());
                }
                
                if ($user->schools) {
                    $mergeRequest->merge($user->schools()->whereHas('requestsReceived',function(Builder $query){
                        $query->where('requestable_type','App\YourEdu\Follow')
                        ->where('state','PENDING');
                    })->with('requestsReceived')->get());
                }
    
                if ($mergeRequest) {
                    return response()->json([
                        'message'=> 'successful',
                        'requests'=> paginate($mergeRequest,10)
                        // 'requests'=> paginate($mergeRequest,10)
                    ]);
                }

                return response()->json([
                    'message'=> 'successful',
                    'requests'=> []
                ]);
            } else {
                return response()->json([
                    'message'=> 'unsuccessful. user does not exist',
                ],422);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function declineRequest($requestId)
    {
        $requestTo = null;
        $mainRequest = YourEduRequest::find($requestId);

        if ($mainRequest) {
            if (Str::contains(strtolower($mainRequest->requestto_type), 'learner')) {
                $requestTo = Learner::find($mainRequest->requestto_id);
            } else if (Str::contains(strtolower($mainRequest->requestto_type), 'facilitator')) {
                $requestTo = Facilitator::find($mainRequest->requestto_id);
            } else if (Str::contains(strtolower($mainRequest->requestto_type), 'professional')) {
                $requestTo = Professional::find($mainRequest->requestto_id);
            } else if (Str::contains(strtolower($mainRequest->requestto_type), 'parent')) {
                $requestTo = ParentModel::find($mainRequest->requestto_id);
            } else if (Str::contains(strtolower($mainRequest->requestto_type), 'school')) {
                $requestTo = School::find($mainRequest->requestto_id);
            }

            if (!$requestTo) {
                return response()->json([
                    'message' => 'unsuccessful. request is to no account'
                ],422);
            }

            if ($requestTo->user_id !== auth()->id()) {
                return response()->json([
                    'message' => 'unsuccessful. request is not to you'
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

        } else {
            return response()->json([
                'message' => 'unsuccessful. request does not exist'
            ],422);
        }
    }
}
