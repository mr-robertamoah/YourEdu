<?php

namespace App\Http\Controllers\Api;

use App\Events\DeleteAttachment;
use App\Events\NewAttachment;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostAttachmentResource;
use App\Services\Attachment;
use App\YourEdu\Admin;
use App\YourEdu\Facilitator;
use App\YourEdu\Grade;
use App\YourEdu\Learner;
use App\YourEdu\ParentModel;
use App\YourEdu\Post;
use App\YourEdu\PostAttachment;
use App\YourEdu\Professional;
use App\YourEdu\School;
use App\YourEdu\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttachmentController extends Controller
{
    //

    public function attachmentCreate(Request $request)
    {
        
        $account = getAccountObject($request->account,$request->accountId); 

        if (is_null($account)) {
            return response()->json([
                'message' => "unsuccessful, {$request->account} does not exist"
            ],422);
        }

        $item = null;
        if ($request->item === 'post') {
            $item = Post::find($request->itemId);
        }

        if (is_null($item)) {
            return response()->json([
                'message' => "unsuccessful, {$request->item} does not exist"
            ],422);
        }

        try {
            DB::beginTransaction();
            
            $attachment = Attachment::attach($account, $item, $attachable, $attachableId, $request->note);

            if ($attachment) {

                DB::commit();
                $attachmentResource = new PostAttachmentResource($attachment);
                broadcast(new NewAttachment([
                    'attachment' => $attachmentResource,
                    'item' => $request->item,
                    'itemId' => $request->itemId
                ]))->toOthers();
                return response()->json([
                    'message' => 'successful',
                    'attachment' => $attachmentResource,
                ]);
            } else {
                return response()->json([
                    'message' => 'unsuccessful, attachment not created'
                ],422);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return response()->json([
                'message' => 'unsuccessful, something happened'
            ],422);
        }
        
    }

    public function attachmentDelete($attachment)
    {
        $mainAttachment = PostAttachment::find($attachment);
        if (!$mainAttachment) {
            return response()->json([
                'message' => 'unsuccessful, attachment does not exist'
            ],422);
        }

        if ($mainAttachment->attachedby->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'unsuccessful, you cannot delete attachment you did not create'
            ],422);
        }

        $item = getAccountString($mainAttachment->attachable_type);
        $itemId = $mainAttachment->attachable_id;
        $mainAttachment->delete();
        broadcast(new DeleteAttachment([
            'attachmentId' => $attachment,
            'item' => $item,
            'itemId' => $itemId,
        ]))->toOthers();

        return response()->json([
            'message' => 'successful'
        ]);
    }
}
