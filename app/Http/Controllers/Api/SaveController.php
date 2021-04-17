<?php

namespace App\Http\Controllers\Api;

use App\DTOs\SaveDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\SavedResource;
use App\Http\Resources\SaveResource;
use App\Services\SaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaveController extends Controller
{
    //

    public function deleteSave(Request $request)
    {   
        try {
            DB::beginTransaction();

            (new SaveService())->deleteSave(
                SaveDTO::createFromRequest($request)
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

    public function createSave(Request $request)
    {
        try {
            DB::beginTransaction();

            $save = (new SaveService())->createSave(
                SaveDTO::createFromRequest($request)
            );

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
