<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ProgramDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProgramRequest;
use App\Http\Requests\ProgramAliasCreateRequest;
use App\Http\Requests\ProgramCreateRequest;
use App\Http\Requests\UpdateProgramRequest;
use App\Http\Resources\DashboardItemResource;
use App\Http\Resources\DashboardProgramResource;
use App\Http\Resources\ProgramResource;
use App\Services\ProgramService;
use App\YourEdu\Program;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{
    public function programCreate(ProgramCreateRequest $request)
    {
        try { 
            DB::beginTransaction();
            
            $program = (new ProgramService())->programCreate($request->account,
                $request->accountId,$request->name,$request->description,
                $request->rationale,json_decode($request->aliases));

            DB::commit();
            return response()->json([
                'message' => "successful",
                'program' => new ProgramResource($program)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            // return response()->json([
            //     'message' => "unsuccessful, something happened."
            // ],422);
        }

    }

    public function programAliasCreate(ProgramAliasCreateRequest $request,$program)
    {
        try {
            DB::beginTransaction();

            $mainProgram = (new ProgramService())->programAliasCreate($program,
                $request->account,$request->accountId,$request->name,$request->description);

            DB::commit();
            return response()->json([
                'message' => "successful",
                'program' => new ProgramResource($mainProgram)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            // return response()->json([
            //     'message' => "unsuccessful, something happened."
            // ],422);
        }

    }

    public function programsGet()
    {
        return ProgramResource::collection(Program::hasNoOwner()->paginate(2));
    }

    public function programsSearch($search)
    {
        $programs = Program::where('name','like',"%{$search}%")
            ->orWhereHas('aliases',function(Builder $query) use ($search){
                $query->where('name','like',"%{$search}%");
            })->hasNoOwner()->get();

        return response()->json([
            'message' => 'successful',
            'programs' => ProgramResource::collection($programs)
        ]);
    }

    public function programsDelete($program)
    {
        try {
            $programInfo = (new ProgramService())->programDelete($program,auth()->id());

            return response()->json([
                'message' => $programInfo
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function createProgram(CreateProgramRequest $request)
    {
        try {
            DB::beginTransaction();
            $program = (new ProgramService())->createProgram(
                ProgramDTO::createFromRequest($request)
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'program' => new DashboardProgramResource($program)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function updateProgram(UpdateProgramRequest $request)
    {
        try {
            DB::beginTransaction();
            $program = (new ProgramService())->updateProgram(
                ProgramDTO::createFromRequest($request)
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'program' => new DashboardProgramResource($program),
                'programResource' => json_decode($request->main) ?
                    new DashboardItemResource($program) : null,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function deleteProgram(Request $request)
    {
        try {
            DB::beginTransaction();
            $program = (new ProgramService())->deleteProgram(
                ProgramDTO::createFromRequest($request)
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'program' => $program ? new DashboardProgramResource($program) : null
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
