<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GradeAliasCreateRequest;
use App\Http\Requests\GradeCreateRequest;
use App\Http\Resources\GradeResource;
use App\Services\GradeService;
use App\YourEdu\Grade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    //

    public function gradeCreate(GradeCreateRequest $request)
    {
        try { 
            DB::beginTransaction();

            $grade = (new GradeService())->gradeCreate($request->account,
                $request->accountId,$request->name,$request->description,
                $request->rationale,json_decode($request->aliases));

            DB::commit();
            return response()->json([
                'message' => "successful",
                'grade' => new GradeResource($grade)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            // return response()->json([
            //     'message' => "unsuccessful, something happened."
            // ],422);
        }

    }

    public function gradeAliasCreate(GradeAliasCreateRequest $request,$grade)
    {
        try {

            DB::beginTransaction();
            
            $mainGrade = (new GradeService())->gradeAliasCreate($grade,
                $request->account,$request->accountId,$request->name,$request->description);

            DB::commit();
            return response()->json([
                'message' => "successful",
                'grade' => new GradeResource($mainGrade)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            // return response()->json([
            //     'message' => "unsuccessful, something happened."
            // ],422);
        }

    }

    public function gradesGet()
    {
        return GradeResource::collection(Grade::paginate(2));
    }

    public function gradesSearch($search)
    {
        $subjects = Grade::where('name','like',"%{$search}%")
            ->orWhereHas('aliases',function(Builder $query) use ($search){
                $query->where('name','like',"%{$search}%");
            })->get(); 

        return response()->json([
            'message' => 'successful',
            'grades' => GradeResource::collection($subjects)
        ]);
    }

    public function gradeDelete($grade)
    {
        try {
            $gradeInfo = (new GradeService())->gradeDelete($grade,auth()->id());

            return response()->json([
                'message' => $gradeInfo
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
