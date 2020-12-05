<?php

namespace App\Http\Controllers\Api;

use App\Events\DeleteRequestMessage;
use App\Events\NewRequestMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Http\Resources\DashboardSchoolRequestResource;
use App\Http\Resources\OwnedProfileResource;
use App\Http\Resources\RequestMessageResource;
use App\Http\Resources\RequestResource;
use App\Http\Resources\UserAndProfileResource;
use App\Services\MessageService;
use App\Services\RequestService;
use Illuminate\Http\Request;
use \Debugbar;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    //

    public function searchAccounts(Request $request)
    {
        $data = (new RequestService())->searchAccounts($request->account,
            $request->search,$request->type,$request->account2);
        
        return UserAndProfileResource::collection(paginate($data,10));
    }

    public function sendAccountsRequest(Request $request)
    {
        dd($request);
        try {
            DB::beginTransaction();
            $accountsRequest = (new RequestService())->sendAccountRequest(
                $request->from,$request->fromId,$request->to,$request->toId,$request->item,
                $request->itemId,[
                    'title' => $request->title,
                    'ward' => $request->ward,
                    'wardId' => $request->wardId,
                    'adminId' => $request->adminId,
                    'level' => $request->level,
                    'salary' => $request->salary,
                    'salaryPeriod' => $request->salaryPeriod,
                    'currency' => $request->currency,
                    'commission' => $request->commission,
                    'description' => $request->description,
                    'attachments' => json_decode($request->attachments),
                ],$request->file('files') ?? [],$request->what
            );

            // DB::commit();
            return response()->json([
                'message' => 'successful',
                'request' => $accountsRequest
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
            $accountRequests = (new RequestService())->getAccountRequests($request->account,
                $request->accountId);

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

            $message = (new RequestService())->sendMessage($requestId,
                $request->account,$request->accountId,auth()->id(),$request->message,
                'sent',$request->file('file'));

            DB::commit();
            $messageResource =  new RequestMessageResource($message);
            broadcast(new NewRequestMessage(
               $messageResource,$requestId))->toOthers();
            return response()->json([
                'message' => 'successful',
                'requestMessage' =>  $messageResource
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return response()->json([
                'message' => 'unsuccessful, something happened.'
            ], 422);
        }
    }
    
    public function deleteMessage(Request $request)
    {
        try {
            DB::beginTransaction();

            $message = (new MessageService())
                    ->deleteMessage(auth()->id(),$request->messageId,'delete',false);
            
            DB::commit();
            broadcast(new DeleteRequestMessage($message['itemId'], 
                $request->requestId))->toOthers();
            return response()->json([
                'message' => 'successful'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
