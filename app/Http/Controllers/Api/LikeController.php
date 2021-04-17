<?php

namespace App\Http\Controllers\Api;

use App\DTOs\LikeDTO;
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

    public function deleteLike(Request $request,$like)
    {
        try {
            DB::beginTransaction();
            (new LikeService)->deleteLike(
                LikeDTO::createFromRequest($request)
            );

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

    public function createLike(Request $request)
    {
        try {
            DB::beginTransaction();
            $like = (new LikeService())->createLike(
                LikeDTO::createFromRequest($request)
            );

            DB::commit();
            
            return response()->json([
                'message' => "successful",
                'like' => new LikeResource($like),
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
