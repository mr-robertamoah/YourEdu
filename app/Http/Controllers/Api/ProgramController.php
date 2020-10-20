<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProgramAliasCreateRequest;
use App\Http\Requests\ProgramCreateRequest;
use App\Http\Resources\ProgramResource;
use App\Services\ProgramService;
use App\YourEdu\Program;
use Illuminate\Database\Eloquent\Builder;
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
        return [
            'message' => 'successful',
            'programs' => ProgramResource::collection(Program::all())
        ];
    }

    public function programsSearch($search)
    {
        $programs = Program::where('name','like',"%{$search}%")
            ->orWhereHas('aliases',function(Builder $query) use ($search){
                $query->where('name','like',"%{$search}%");
            })->get();

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
}
