<?php

namespace App\Http\Controllers\Api;

use App\DTOs\FlagDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\FlaggedResource;
use App\Http\Resources\FlagResource;
use App\Services\FlagService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Debugbar;

class FlagController extends Controller
{
    //

    public function deleteFlag(Request $request)
    {
        try {
            DB::beginTransaction();

            (new FlagService())->deleteFlag(
                FlagDTO::createFromRequest($request)
            );

            DB::commit();
            return response()->json([
                'message' => 'successful'
            ]);
        } catch (\Throwable $th) {
            throw $th;
            // return response()->json([
            //     'message' => "successful"
            // ],422);
        }
    }

    public function createFlag(Request $request)
    {
        try {
            DB::beginTransaction();

            $flag = (new FlagService())->createFlag(
                FlagDTO::createFromRequest($request)
            );

            DB::commit();
            return response()->json([
                'message' => "successful",
                'flag' => new FlagResource($flag),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            // return response()->json([
            //     'message' => "unsuccessful"
            // ],422);
            throw $th;
        }
    }

    public function userFlaggedGet(Request $request)
    {
        $flags = (new flagService())->userFlaggedGet($request->type);

        return FlaggedResource::collection(paginate($flags->sortByDesc('updated_at'), 5));
    }
    
}
