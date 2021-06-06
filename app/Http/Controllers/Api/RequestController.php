<?php

namespace App\Http\Controllers\Api;

use App\DTOs\AccountDTO;
use App\DTOs\MessageDTO;
use App\DTOs\RequestDTO;
use App\DTOs\RequestSearchDTO;
use App\DTOs\ResponseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Http\Resources\DashboardItemMiniResource;
use App\Http\Resources\DashboardSchoolRequestResource;
use App\Http\Resources\OwnedProfileResource;
use App\Http\Resources\RequestMessageResource;
use App\Http\Resources\RequestResource;
use App\Http\Resources\UserAndProfileResource;
use App\Services\RequestService;
use Illuminate\Http\Request;
use \Debugbar;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    //

    public function searchAccounts(Request $request)
    {
        $data = (new RequestService())->searchAccounts(
            RequestSearchDTO::createFromRequest($request)
        );
        
        ray($data)->green();
        return UserAndProfileResource::collection(paginate($data,10));
    }

    public function searchItems(Request $request)
    {
        $data = (new RequestService())->searchItems(
            RequestSearchDTO::createFromRequest($request)
        );
        
        ray($data)->green();
        return DashboardItemMiniResource::collection($data);
    }

    public function createAccountRequests(Request $request)
    {
        try {
            DB::beginTransaction();
            (new RequestService())->createAccountRequests(
                RequestDTO::createFromRequest($request)
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

    public function createAccountResponse(Request $request)
    {
        try {
            DB::beginTransaction();

            (new RequestService())->createAccountResponse(
                ResponseDTO::createFromRequest($request)
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
    
    public function schoolRelatedResponse(Request $request)
    {
        try {
            DB::beginTransaction();
            $requestFrom = (new RequestService())->schoolRelatedResponse(
                $request->requestId,$request->action,
                $request->other,auth()->id(),$request->mine);

            DB::commit();
            $data = null;
            if ($request->mine === 'admin') {
                if ($request->other === 'school') {
                    $data = new OwnedProfileResource($requestFrom->profile);
                }
            } 
            return response()->json([
                'message' => 'successful',
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function getUserRequests()
    {
        try {

            $request = (new RequestService())->getUserRequests(auth()->id());

            return RequestResource::collection(paginate($request,10));
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function getAccountRequests(Request $request)
    {
        try {
            $accountRequests = (new RequestService())->getAccountRequests(
                AccountDTO::createFromRequest($request)
            );

            return DashboardSchoolRequestResource::collection(paginate($accountRequests,2));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function declineRequest($requestId)
    {
        try {
            (new RequestService())->declineRequest($requestId,auth()->id());

            return response()->json([
                'message' => 'successful'
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getMessages(Request $request)
    {
        try {
            $messages = (new RequestService())->getMessages($request->requestId);

            return RequestMessageResource::collection($messages);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function sendMessage(MessageRequest $request, $requestId)
    {
        try {
            DB::beginTransaction();

            $message = (new RequestService)->sendMessage(
                MessageDTO::createFromRequest($request)
                    ->addData(
                        item: 'request',
                        itemId: $requestId,
                        state: 'sent',
                    )
                    ->addFile(
                        $request->file('file')
                    )
            );

            DB::commit();

            return response()->json([
                'message' => 'successful',
                'requestMessage' =>  new RequestMessageResource($message)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return response()->json([
                'message' => 'unsuccessful, something happened.'
            ], 422);
        }
    }
    
    public function deleteMessage(MessageRequest $request, $messageId)
    {
        try {
            DB::beginTransaction();

            (new RequestService)->deleteMessage(
                MessageDTO::createFromRequest($request)->addData(
                    action: 'delete',
                    messageId: $messageId
                )
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
}
