<?php

namespace App\Http\Controllers\Api;

use App\DTOs\LessonDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use App\Http\Resources\DashboardItemResource;
use App\Http\Resources\DashboardLessonResource;
use App\Services\LessonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LessonController extends Controller
{
    public function createLesson(CreateLessonRequest $request)
    {
        try {
            DB::beginTransaction();
            $lesson = (new LessonService())->createLesson(
                LessonDTO::createFromRequest($request, true)
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'lesson' => new DashboardLessonResource($lesson)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function updateLesson(UpdateLessonRequest $request)
    {
        try {
            DB::beginTransaction();
            $lesson = (new LessonService())->updateLesson(
                LessonDTO::createFromRequest($request, true)
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'lesson' => new DashboardLessonResource($lesson),
                'lessonResource' => json_decode($request->main) ?
                    new DashboardItemResource($lesson) : null,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function deleteLesson(Request $request)
    {
        try {
            DB::beginTransaction();
            $lesson = (new LessonService())->deleteLesson(
                LessonData::createFromRequest($request, true)
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'lesson' => $lesson ? new DashboardLessonResource($lesson) : null
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
