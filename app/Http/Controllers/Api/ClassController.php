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
            $class = (new ClassService())->createCLass(
                $request->account,
                $request->accountId,
                auth()->id(),
                [
                    'owner' => $request->owner,
                    'ownerId' => $request->ownerId,
                    'name' => $request->name,
                    'description' => $request->description,
                    'gradeId' => $request->gradeId,
                    'facilitate' => json_decode($request->facilitate),
                    'maxLearners' => json_decode($request->maxLearners),
                    'type' => $request->type,
                    'paymentData' => json_decode($request->paymentData),
                    'discussionData' => json_decode($request->discussionData),
                    'discussionFiles' => $request->file('discussionFile'),
                    'feeable' => $request->feeable,
                    'feeableId' => $request->feeableId,
                    'structure' => $request->structure,
                ]);

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
                $request->account,
                $request->accountId,
                $request->classId,
                auth()->id(),
                [
                    'name' => $request->name,
                    'description' => $request->description,
                    'state' => $request->state,
                    'maxLearners' => $request->maxLearners, 
                ],
                $request->gradeId,
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
                $request->adminId,
                $request->action,
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
