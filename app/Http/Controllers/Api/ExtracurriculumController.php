<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ExtracurriculumData;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExtracurriculumCreateRequest;
use App\Http\Resources\DashboardExtracurriculumResource;
use App\Http\Resources\DashboardItemResource;
use App\Http\Resources\ExtracurriculumResource;
use App\Services\ExtracurriculumService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExtracurriculumController extends Controller
{
    
    public function createExtracurriculum(ExtracurriculumCreateRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $extracurriculum = (new ExtracurriculumService())->createExtracurriculum(
                ExtracurriculumData::createFromRequest($request)
            );

            DB::commit();
            return response()->json([
                'message' => "successful",
                'extracurriculum' => new DashboardExtracurriculumResource($extracurriculum)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            // return response()->json([
            //     'message' => "unsuccessful, something happened."
            // ],422);
        }
    }

    public function getExtracurriculum(Request $request)
    {
        try {
            $course = (new ExtracurriculumService())->getExtracurriculum(
                $request->courseId
            );

            return new DashboardExtracurriculumResource($course);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getExtracurriculums(Request $request)
    {
        try {
            $courses = (new ExtracurriculumService())->getExtracurriculums();

            return DashboardExtracurriculumResource::collection($courses);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateExtracurriculum(Request $request)
    {
        try {
            DB::beginTransaction();
            $extracurriculum = (new ExtracurriculumService())->updateExtracurriculum(
                ExtracurriculumData::createFromRequest($request)
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'extracurriculum' => new DashboardExtracurriculumResource($extracurriculum),
                'extracurriculumResource' => json_decode($request->main) ?
                    new DashboardItemResource($extracurriculum) : null,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function deleteExtracurriculum(Request $request)
    {
        try {
            DB::beginTransaction();
            $extracurriculum = (new ExtracurriculumService())->deleteExtracurriculum(
                ExtracurriculumData::createFromRequest($request)
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'extracurriculum' => $extracurriculum ? new DashboardExtracurriculumResource($extracurriculum) : null
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
