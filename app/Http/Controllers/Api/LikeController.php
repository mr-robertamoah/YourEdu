<?php

namespace App\Http\Controllers\Api;

use App\Events\DeleteLike;
use App\Events\NewLike;
use App\Http\Controllers\Controller;
use App\Http\Resources\LikeResource;
use App\Services\LikeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    //

    public function likeDelete(Request $request,$like)
    {
        try {
            DB::beginTransaction();
            $likeData =(new LikeService())->likeDelete($like,auth()->id());

            broadcast(new DeleteLike([
                'likeId' => $like,
                'item' => $likeData['item'],
                'itemId' => $likeData['itemId'],
                'itemBelongsTo' => $likeData['itemBelongsTo'],
                'itemBelongsToId' => $likeData['itemBelongsToId'],
            ]))->toOthers();
            DB::commit();
            return response()->json([
                'message' => "successful"
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            // return response()->json([
            //     'message' => "successful"
            // ],422);
        }
    }

    public function likeCreate(Request $request,$item, $itemId)
    {
        
        try {
            DB::beginTransaction();
            $likeData = (new LikeService())->likeCreate($request->account,$request->accountId,
            $item,$itemId, auth()->id());

            DB::commit();
            broadcast(new NewLike([
                'like' => new LikeResource($likeData['like']),
                'item' => $item,
                'itemId' => $itemId,
                'itemBelongsTo' => $likeData['itemBelongsTo'],
                'itemBelongsToId' => $likeData['itemBelongsToId'],
            ]))->toOthers();
            return response()->json([
                'message' => "successful",
                'like' => new LikeResource($likeData['like']),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            // return response()->json([
            //     'message' => "unsuccessful"
            // ],422);
            throw $th;
        }
    }
}
