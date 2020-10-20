<?php

namespace App\Http\Controllers\Api;

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

    public function flagDelete($flag)
    {
        try {
            $flagInfo = (new FlagService())->flagDelete($flag,auth()->id());
            return response()->json([
                'message' => $flagInfo
            ]);
        } catch (\Throwable $th) {
            throw $th;
            // return response()->json([
            //     'message' => "successful"
            // ],422);
        }
    }

    public function flagCreate(Request $request,$item, $itemId)
    {
        try {
            DB::beginTransaction();

            $flag = (new FlagService())->flagCreate($request->account,$request->accountId,
                $item,$itemId,$request->reason,auth()->id());

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

        Debugbar::info($flags);
        return FlaggedResource::collection(paginate($flags->sortByDesc('updated_at'), 5));
    }
    
}
