<?php

namespace App\Http\Controllers\Api;

use App\DTOs\AssessmentData;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAssessmentRequest;
use App\Http\Requests\DeleteAssessmentRequest;
use App\Http\Requests\UpdateAssessmentRequest;
use App\Http\Resources\AssessmentResource;
use App\Services\AssessmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    //
    public function createAssessment(CreateAssessmentRequest $request)
    {
        try {
            DB::beginTransaction();
            $assessment = (new AssessmentService())->createAssessment(
                AssessmentData::createFromRequest($request, true)
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'Assessment' => new AssessmentResource($assessment)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function updateAssessment(UpdateAssessmentRequest $request)
    {
        try {
            DB::beginTransaction();
            $assessment = (new AssessmentService())->updateAssessment(
                AssessmentData::createFromRequest($request, true)
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'Assessment' => new AssessmentResource($assessment),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function deleteAssessment(DeleteAssessmentRequest $request)
    {
        try {
            DB::beginTransaction();
            $assessment = (new AssessmentService())->deleteAssessment(
                AssessmentData::createFromRequest($request, true)
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'Assessment' => $assessment ? new AssessmentResource($assessment) : null
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
