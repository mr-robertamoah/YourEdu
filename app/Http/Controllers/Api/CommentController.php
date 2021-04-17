<?php

namespace App\Http\Controllers\Api;

use App\DTOs\CommentDTO;
use App\Events\DeleteComment;
use App\Events\NewComment;
use App\Events\UpdateComment;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentCreateRequest;
use App\Http\Requests\CommentEditRequest;
use App\Http\Resources\CommentResource;
use App\Services\CommentService;
use App\YourEdu\Comment;
use Illuminate\Support\Facades\DB;
use \Debugbar;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function createComment(CommentCreateRequest $request)
    {        
        try {
            DB::beginTransaction();
            
            $comment = (new CommentService())->createComment(
                CommentDTO::createFromRequest($request)    
            );

            DB::commit();
            
            return response()->json([
                'message' => "successful",
                'comment' => new CommentResource($comment),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            // return response()->json([
            //     'message' => "Unsuccessful. Something might have gone wrong. Please try again later."
            // ]);
            throw $th;
        }
    }

    public function updateComment(CommentEditRequest $request)
    {
       try {
            DB::beginTransaction();

            $comment = (new CommentService())->updateComment(
                CommentDTO::createFromRequest($request)    
            );

            DB::commit();
            
            return response()->json([
                'message' => "successful",
                'comment' => new CommentResource($comment),
            ]);
            
        } catch (\Throwable $th) {
            DB::rollback();
            // return response()->json([
            //     'message' => "Unsuccessful. Something might have gone wrong. Please try again later."
            // ]);
            throw $th;
        }
    }

    public function deleteComment(Request $request)
    {
        try {
            DB::beginTransaction();

            (new CommentService())->deleteComment(
                CommentDTO::createFromRequest($request)
            );
            
            DB::commit();
            return response()->json([
                'message' => "successful"
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            // return response()->json([
            //     'message' => "unsuccessful"
            // ]);
        }
    }

    public function getComments($item, $itemId)
    {
        $comments = (new CommentService)->getComments(
            CommentDTO::createFromData(
                item: $item, itemId: $itemId
            )
        );

        return CommentResource::collection(paginate($comments,5));
    }

    public function getComment($commentId)
    {
        $item = Comment::find($commentId);
        
        if (!$item) {
            return response()->json([
                'message' => 'unsuccessful. comment does not exist.'
            ]);
        }
        return response()->json([
            'message' => "successful",
            'comment' => new CommentResource($item)
        ]);
    }
}
