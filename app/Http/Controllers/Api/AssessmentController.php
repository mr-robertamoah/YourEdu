<?php

namespace App\Http\Controllers\Api;

use App\DTOs\AssessmentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAssessmentRequest;
use App\Http\Requests\DeleteAssessmentRequest;
use App\Http\Requests\UpdateAssessmentRequest;
use App\Http\Resources\AssessmentResource;
use App\Services\AssessmentService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    public function createAssessment(CreateAssessmentRequest $request)
    {
        $assessment = (new AssessmentService())->createAssessment(
            AssessmentDTO::createFromRequest($request, true)
        );

        DB::commit();
        return response()->json([
            'message' => 'successful',
            'assessment' => new AssessmentResource($assessment)
        ]);
    }

    public function updateAssessment(UpdateAssessmentRequest $request)
    {
        $assessment = (new AssessmentService())->updateAssessment(
            AssessmentDTO::createFromRequest($request, true)
        );
        // UploadedFile::
        DB::commit();
        return response()->json([
            'message' => 'successful',
            'assessment' => new AssessmentResource($assessment),
        ]);
    }

    public function deleteAssessment(DeleteAssessmentRequest $request)
    {
        $assessment = (new AssessmentService())->deleteAssessment(
            AssessmentDTO::createFromRequest($request, true)
        );

        DB::commit();
        return response()->json([
            'message' => 'successful',
            'assessment' => $assessment instanceof Model ?
                new AssessmentResource($assessment) : null
        ]);
    }
    
    public function getWork(Request $request)
    {
        $assessmentResource = (new AssessmentService())->getWork(
            AssessmentDTO::createFromRequest($request, true)
        );

        return response()->json([
            'message' => 'successful',
            'assessment' => $assessmentResource
        ]);
    }
}
