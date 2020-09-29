<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProgramResource;
use App\Services\Attachment;
use App\YourEdu\Program;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramController extends Attachment
{
    public function programCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'rationale' => 'nullable|string',
            'aliases' => 'nullable|array',
        ]);
        if ($request->account === 'learner' || $request->account === 'parent') {
            return response()->json([
                'message' => 'unsuccessful, learner or parent can only create an alias of a program'
            ],422);
        }

        try { 

            DB::beginTransaction();
            
            $program = $this->createAttachment($request, 'program');

            if (is_null($program)) {
                return response()->json([
                    'message' => 'unsuccessful'
                ],422);
            }

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

    public function programAliasCreate(Request $request,$program)
    {
        $mainProgram = Program::find($program);

        if (!$mainProgram) { 
            return response()->json([
                'message' => 'unsuccessful, program does not exist'
            ],422);
        }

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {

            DB::beginTransaction();

            $alias = $this->createAttachmentAlias($request,$mainProgram);

            if (is_null($alias)) {
                return response()->json([
                    'message' => "unsuccessful, alias was not created."
                ]);
            }

            DB::commit();
            $mainProgram = Program::find($mainProgram->id);
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
        $mainProgram = Program::find($program);
        if (!$mainProgram) {
            return response()->json([
                'message' => 'unsuccessful, program does not exist'
            ],422);
        }

        if ($mainProgram->addedby->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'unsuccessful, you cannot delete program you did not create'
            ],422);
        }

        $mainProgram->delete();

        return response()->json([
            'message' => 'successful'
        ]);
    }
}
