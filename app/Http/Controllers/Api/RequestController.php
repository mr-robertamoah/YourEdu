<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RequestResource;
use App\User;
use App\YourEdu\Facilitator;
use App\YourEdu\Follow;
use App\YourEdu\Learner;
use App\YourEdu\Message;
use App\YourEdu\ParentModel;
use App\YourEdu\Professional;
use App\YourEdu\Request as YourEduRequest;
use App\YourEdu\School;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RequestController extends Controller
{
    //

    public function getUserRequests()
    {
        try {
            $id = auth()->id();
            $request = YourEduRequest::where('state','PENDING')
                ->whereHasMorph('requestto','*',function(Builder $query, $type) use ($id){
                    if ($type == 'App\YourEdu\School') {
                        $query->where('owner_id',$id);
                    } else{
                        $query->where('user_id',$id);
                    }
                })
                ->with(['requestfrom.profile'])
                ->with(['requestable'=>function(MorphTo $query){
                    $query->morphWith([
                        Message::class =>['images','videos','audios','files',],
                        Discussion::class =>['images','videos','audios','files','likes',
                            'comments','beenSaved','raisedby.profile','attachments',
                            'participants'],
                    ]);
                }])->get();

            // return $request;
            return RequestResource::collection(paginate($request->sortByDesc('updated_at'),10));
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function declineRequest($requestId)
    {
        $requestTo = null;
        $mainRequest = YourEduRequest::find($requestId);

        if ($mainRequest) {
            $requestTo = getAccountObject(getAccountString($mainRequest->requestto_type),
                $mainRequest->requestto_id);

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
