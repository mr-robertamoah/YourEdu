<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ClassDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateClassRequest;
use App\Http\Resources\ClassResource;
use App\Http\Resources\DashboardClassResource;
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
                ClassDTO::createFromRequest($request)
            );

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
                ClassDTO::createFromRequest($request)
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'class' => new ClassResource($class),
                'classResource' => json_decode($request->main) ?
                new DashboardItemResource($class) : null,
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
            $class = (new ClassService())->deleteCLass(
                ClassData::createFromRequest($request)
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'class' => $class ? new DashboardClassResource($class) : null
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
