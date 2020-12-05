<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateClassRequest;
use App\Http\Resources\ClassResource;
use App\Http\Resources\DashboardItemResource;
use App\Services\ClassService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    public function createClass(CreateClassRequest $request)
    {
        try {
            DB::beginTransaction();
            $class = (new ClassService())->createCLass($request->account,$request->accountId,
                auth()->id(),$request->owner,$request->ownerId,
                $request->name,$request->description,$request->gradeId,
                json_decode($request->facilitate),json_decode($request->maxLearners),
                $request->type,json_decode($request->paymentData),
                $request->feeable,$request->feeableId,$request->structure);

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'class' => new ClassResource($class)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function getClass(Request $request)
    {
        
    }

    public function updateClass(Request $request)
    {
        try {
            DB::beginTransaction();
            $class = (new ClassService())->updateCLass(
                $request->classId,
                auth()->id(),
                $request->name,
                $request->description,
                $request->state,
                $request->maxLearners, //adminId,gradeId
                $request->gradeId,
                $request->adminId,
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'class' => new ClassResource($class),
                'classResource' => new DashboardItemResource($class),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function deleteClass(Request $request)
    {
        try {
            DB::beginTransaction();
            (new ClassService())->deleteCLass(
                $request->classId,
                auth()->id(),
                $request->adminId
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
