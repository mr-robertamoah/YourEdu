<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExtracurriculumCreateRequest;
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
                $request->account,
                $request->accountId,
                auth()->id(),
                [
                    'owner' => $request->owner,
                    'ownerId' => $request->ownerId,
                    'name' => $request->name,
                    'description' => $request->description,
                    'attachments' => json_decode($request->attachments),
                    'classes' => json_decode($request->classes),
                    'facilitate' => json_decode($request->facilitate),
                    'type' => $request->type,
                    'discussionData' => json_decode($request->discussionData),
                    'discussionFiles' => $request->file('discussionFile'),
                    'paymentData' => json_decode($request->paymentData)
                ]
            );

            DB::commit();
            return response()->json([
                'message' => "successful",
                'extracurriculum' => new ExtracurriculumResource($extracurriculum)
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

            return new ExtracurriculumResource($course);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getExtracurriculums(Request $request)
    {
        try {
            $courses = (new ExtracurriculumService())->getExtracurriculums();

            return ExtracurriculumResource::collection($courses);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateExtracurriculum(Request $request)
    {
        try {
            DB::beginTransaction();
            $extracurriculum = (new ExtracurriculumService())->updateExtracurriculum(
                $request->account,
                $request->accountId,
                $request->extracurriculumId,
                auth()->id(),
                [
                    'name' => $request->name,
                    'description' => $request->description,
                    'state' => $request->state,
                    'attachments' => json_decode($request->attachments),
                    'removedAttachments' => json_decode($request->removedAttachments),
                    'name' => $request->adminId,
                ]
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'extracurriculum' => new ExtracurriculumResource($extracurriculum),
                'extracurriculumResource' => new DashboardItemResource($extracurriculum),
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
                $request->extracurriculumId,
                auth()->id(),
                $request->adminId,
                $request->action
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'extracurriculum' => $extracurriculum ? new ExtracurriculumResource($extracurriculum) : null
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
