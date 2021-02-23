<?php

namespace App\Http\Controllers\Api;

use App\DTOs\CollaborationData;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCollaborationRequest;
use App\Http\Requests\DeleteCollaborationRequest;
use App\Http\Requests\UpdateCollaborationRequest;
use App\Http\Resources\DashboardCollaborationResource;
use App\Http\Resources\DashboardItemResource;
use App\Services\CollaborationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollaborationController extends Controller
{
    //
    public function createCollaboration(CreateCollaborationRequest $request)
    {
        try {
            DB::beginTransaction();
            $collaboration = (new CollaborationService())->createCollaboration(
                CollaborationData::createFromRequest($request, true)
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'collaboration' => new DashboardCollaborationResource($collaboration)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function updateCollaboration(UpdateCollaborationRequest $request)
    {
        try {
            DB::beginTransaction();
            $collaboration = (new CollaborationService())->updateCollaboration(
                CollaborationData::createFromRequest($request, true)
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'collaboration' => new DashboardCollaborationResource($collaboration),
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function deleteCollaboration(DeleteCollaborationRequest $request)
    {
        try {
            DB::beginTransaction();
            $collaboration = (new CollaborationService())->deleteCollaboration(
                CollaborationData::createFromRequest($request, true)
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'collaboration' => $collaboration ? new DashboardCollaborationResource($collaboration) : null
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
