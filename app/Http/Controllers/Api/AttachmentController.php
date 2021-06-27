<?php

namespace App\Http\Controllers\Api;

use App\DTOs\AttachmentDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostAttachmentResource;
use App\Services\AttachmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttachmentController extends Controller
{
    public function createAttachment(Request $request)
    {
        try {
            DB::beginTransaction();

            $attachment = (new AttachmentService())->createAttachment(
                AttachmentDTO::createFromRequest($request)
            );

            DB::commit();

            return response()->json([
                'message' => 'successful',
                'attachment' =>  new PostAttachmentResource($attachment),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return response()->json([
                'message' => 'unsuccessful, something happened'
            ], 422);
        }
    }

    public function deleteAttachment(Request $request)
    {
        try {
            DB::beginTransaction();

            (new AttachmentService())->deleteAttachment(
                AttachmentDTO::createFromRequest($request)
            );

            DB::commit();

            return response()->json([
                'message' => 'successful'
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
