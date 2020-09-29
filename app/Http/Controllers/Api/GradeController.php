<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\GradeResource;
use App\Services\Attachment;
use App\YourEdu\Grade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends Attachment
{
    //

    public function gradeCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'ageGroup' => 'nullable|string',
            'aliases' => 'nullable|array',
        ]);
        if ($request->account === 'learner' || $request->account === 'parent') {
            return response()->json([
                'message' => 'unsuccessful, learner or parent can only create an alias of a program'
            ],422);
        }

        try { 

            DB::beginTransaction();
            $grade = $this->createAttachment($request, 'grade');

            if (!$grade) {
                return response()->json([
                    'message' => 'unsuccessful, grade was not created'
                ],422);
            }

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

    public function gradeAliasCreate(Request $request,$grade)
    {
        $mainGrade = Grade::find($grade);

        if (!$mainGrade) {
            return response()->json([
                'message' => 'unsuccessful, grade does not exist'
            ],422);
        }

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {

            DB::beginTransaction();
            $alias = $this->createAttachmentAlias($request,$mainGrade);

            if (!$alias) {
                return response()->json([
                    'message' => 'unsuccessful, alias was not created'
                ],422);
            }

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
        return [
            'message' => 'successful',
            'grades' => GradeResource::collection(Grade::all())
        ];
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
        $mainGrade = Grade::find($grade);
        if (!$mainGrade) {
            return response()->json([
                'message' => 'unsuccessful, grade does not exist'
            ],422);
        }

        if ($mainGrade->addedby->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'unsuccessful, you cannot delete grade you did not create'
            ],422);
        }

        $mainGrade->delete();

        return response()->json([
            'message' => 'successful'
        ]);
    }
}
