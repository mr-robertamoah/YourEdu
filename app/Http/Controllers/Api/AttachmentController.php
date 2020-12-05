<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostAttachmentResource;
use App\Jobs\AttachmentCreatedJob;
use App\Jobs\AttachmentRemovedJob;
use App\Services\AttachmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttachmentController extends Controller
{
    public function attachmentCreate(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $attachment = (new AttachmentService())->attachmentCreate($request->account,
                $request->accountId,$request->item,$request->itemId,$request->attachable,
                $request->attachableId, $request->note, $request->adminId);

            DB::commit();
            AttachmentCreatedJob::dispatch($attachment,$request->item,$request->itemId);
            return response()->json([
                'message' => 'successful',
                'attachment' =>  new PostAttachmentResource($attachment),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return response()->json([
                'message' => 'unsuccessful, something happened'
            ],422);
        }        
    }

    public function attachmentDelete($attachment,Request $request)
    {
        try {
            $attachmentInfo = (new AttachmentService())->attachmentDelete($attachment,
                auth()->id(), $request->adminId);

            AttachmentRemovedJob::dispatch($attachment,$attachmentInfo['item'],
                $attachmentInfo['itemId']);
    
            return response()->json([
                'message' => 'successful'
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }       
    }
}
