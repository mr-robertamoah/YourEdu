<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostAttachmentResource;
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
        
        $account = null;

        if ($request->account === 'learner') { 
            $account = Learner::find($request->accountId);
        } else if ($request->account === 'parent') {
            $account = ParentModel::find($request->accountId);
        } else if ($request->account === 'facilitator') {
            $account = Facilitator::find($request->accountId);
        } else if ($request->account === 'professional') {
            $account = Professional::find($request->accountId);
        } else if ($request->account === 'admin') {
            $account = Admin::find($request->accountId);
        } else if ($request->account === 'school') {
            $account = School::find($request->accountId);
        } else {
            return response()->json([
                'message' => "unsuccessful, {$request->account} is not a valid account to create an alias for a grade"
            ],422);
        } 

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

        $attachable = null;
        if ($request->attachable === 'subject') {
            $attachable = Subject::find($request->attachableId);
        } else if ($request->attachable === 'grade') {
            $attachable = Grade::find($request->attachableId);
        }

        if (is_null($attachable)) {
            return response()->json([
                'message' => "unsuccessful, {$request->attachable} does not exist"
            ],422);
        }

        try {
            DB::beginTransaction();
            $attachment = $account->attachments()->create([
                'note' => $request->note
            ]);

            if ($attachment) {
                $attachment->attachedwith()->associate($attachable);
                $attachment->attachable()->associate($item);
                $attachment->save();

                $account->point->value += 1;
                $account->point->save();

                DB::commit();
                return response()->json([
                    'message' => 'successful',
                    'attachment' => new PostAttachmentResource($attachment),
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

        $mainAttachment->delete();

        return response()->json([
            'message' => 'successful'
        ]);
    }
}
