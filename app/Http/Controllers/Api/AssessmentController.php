<?php

namespace App\Http\Controllers\Api;

use App\DTOs\AssessmentDTO;
use App\DTOs\InvitationDTO;
use App\DTOs\SearchDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAssessmentRequest;
use App\Http\Requests\DeleteAssessmentRequest;
use App\Http\Requests\UpdateAssessmentRequest;
use App\Http\Resources\AssessmentResource;
use App\Http\Resources\DiscussionParticipantResource;
use App\Http\Resources\DiscussionPendingParticipantsResource;
use App\Http\Resources\ParticipantProfileResource;
use App\Http\Resources\ParticipantResource;
use App\Http\Resources\UserAccountResource;
use App\Services\AssessmentService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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

    public function getParticipants($assessmentId)
    {
        $profiles = (new AssessmentService)->getParticipants(
            AssessmentDTO::new()->addData(assessmentId: $assessmentId)
        );

        return ParticipantProfileResource::collection($profiles);
    }

    public function updateAssessmentParticipant(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $participant = (new AssessmentService())->updateAssessmentParticipant(
                AssessmentDTO::createFromRequest($request, true)
            );

            DB::commit();

            return response()->json([
                'message' => 'successful',
                'participant' => new ParticipantResource($participant),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function deleteAssessmentParticipant(Request $request)
    {
        try {
            DB::beginTransaction();
            
            (new AssessmentService())->deleteAssessmentParticipant(
                AssessmentDTO::createFromRequest($request)
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

    public function deleteAssessmentMarker(Request $request)
    {
        try {
            DB::beginTransaction();
            
            (new AssessmentService())->deleteAssessmentMarker(
                AssessmentDTO::new()->addData(
                    participantId: $request->markerId,
                    userId: $request->user()?->id,
                )
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
    
    public function invitationResponse(Request $request)
    {
        try {
            DB::beginTransaction();

            $participant = (new AssessmentService())->invitationResponse(
                InvitationDTO::createFromRequest($request)
            );
            
            DB::commit();

            return response()->json([
                'message' => 'successful',
                'participant' => $participant ? 
                    new DiscussionParticipantResource($participant) : $participant
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    
    public function joinResponse(Request $request)
    {
        try {
            DB::beginTransaction();
            $account = (new AssessmentService())->joinResponse(
                InvitationDTO::createFromRequest($request)
            );
            
            DB::commit();
            return response()->json([
                'message' => 'successful',
                'participant' => AssessmentService::getParticipantOrNull($account),
                'marker' => AssessmentService::getMarkerOrNull($account),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function joinAssessment(Request $request)
    {
        try {
            DB::beginTransaction();

            $account = (new AssessmentService())->joinAssessment(
                InvitationDTO::createFromRequest($request)
            );

            DB::commit();

            return response()->json([
                'message' => 'successful',
                'participant' => AssessmentService::getParticipantOrNull($account),
                'marker' => AssessmentService::getMarkerOrNull($account),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function inviteParticipant(Request $request)
    {
        try {
            DB::beginTransaction();

            $account = (new AssessmentService())->inviteParticipant(
                InvitationDTO::createFromRequest($request)
            );

            DB::commit();

            return response()->json([
                'message' => 'successful',
                'pendingParticipant' => $account ? 
                    new DiscussionPendingParticipantsResource($account) : null
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function assessmentSearch(Request $request)
    {
        try {
            $accounts = (new AssessmentService())->assessmentSearch(
                SearchDTO::createFromRequest($request)
            );

            return UserAccountResource::collection($accounts);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
