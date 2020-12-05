<?php

namespace App\Http\Controllers\Api;

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
    //

    public function commentCreate(CommentCreateRequest $request, $item, $itemId)
    {        
        try {
            DB::beginTransaction();
            
            $commentData = (new CommentService())->commentCreate($request->body,
                $request->file('file'),$request->account,$request->accountId,
                auth()->id(),$item,$itemId,$request->adminId);
            broadcast(new NewComment([
                'comment' => new CommentResource($commentData['comment']),
                'item' => $item,
                'itemId' => $itemId,
                'commentable_owner' => $commentData['commentableOwner']
            ]))->toOthers();
            DB::commit();
            
            return response()->json([
                'message' => "successful",
                'comment' => new CommentResource($commentData['comment']),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            // return response()->json([
            //     'message' => "Unsuccessful. Something might have gone wrong. Please try again later."
            // ]);
            throw $th;
        }
    }

    public function commentEdit(CommentEditRequest $request, $comment)
    {
       try {
            DB::beginTransaction();

            $commentData = (new CommentService())->commentEdit($request->account,
                $request->accountId,auth()->id(),$comment,$request->body,$request->adminId);

            DB::commit();
            broadcast(new UpdateComment(
                new CommentResource($commentData['comment']),
                $commentData['comment']
            ))->toOthers();
            return response()->json([
                'message' => "successful",
                'comment' => new CommentResource($commentData['comment']),
            ]);
            
        } catch (\Throwable $th) {
            DB::rollback();
            // return response()->json([
            //     'message' => "Unsuccessful. Something might have gone wrong. Please try again later."
            // ]);
            throw $th;
        }
    }

    public function commentDelete($comment,Request $request)
    {
        try {
            DB::beginTransaction();
            $commentData = (new CommentService())->commentDelete($comment,$request->adminId);
            broadcast(new DeleteComment([
                'commentId' => $comment,
                'item' => $commentData['item'],
                'itemId' => $commentData['itemId'],
                'account' => $commentData['account'],
                'accountId' => $commentData['accountId'],
            ]))->toOthers();
            DB::commit();
            Debugbar::info($commentData);
            return response()->json([
                'message' => "successful"
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return response()->json([
                'message' => "unsuccessful"
            ]);
        }
    }

    public function commentsGet($item, $itemId)
    {
        $comments = (new CommentService)->commentsGet($item,$itemId);

        return CommentResource::collection(paginate($comments,5));
    }

    public function commentGet($comment)
    {
        $item = Comment::find($comment);
        
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
