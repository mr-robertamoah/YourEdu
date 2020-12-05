<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SavedResource;
use App\Http\Resources\SaveResource;
use App\Services\SaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaveController extends Controller
{
    //

    public function saveDelete($save,Request $request)
    {   
        try {
            $saveInfo = (new SaveService())->saveDelete($save,auth()->id(),
            $request->adminId);
            return response()->json([
                'message' => $saveInfo
            ]);
        } catch (\Throwable $th) {
            throw $th;
            // return response()->json([
            //     'message' => "successful"
            // ],422);
        }
    }

    public function saveCreate(Request $request,$item, $itemId)
    {
        try {
            DB::beginTransaction();

            $save = (new SaveService())->saveCreate($request->account,$request->accountId,
                $item,$itemId,auth()->id(),$request->adminId);

            DB::commit();
            return response()->json([
                'message' => "successful",
                'save' => new SaveResource($save),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            // return response()->json([
            //     'message' => "unsuccessful"
            // ],422);
            throw $th;
        }
    }

    public function userSavedGet(Request $request)
    {
        $saves = (new SaveService())->userSavedGet($request->type);

        return SavedResource::collection(paginate($saves->sortByDesc('updated_at'), 5));
    }
}
