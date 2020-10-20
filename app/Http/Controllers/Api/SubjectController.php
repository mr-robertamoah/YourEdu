<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectAliasCreateRequest;
use App\Http\Requests\SubjectCreateRequest;
use App\Http\Resources\SubjectResource;
use App\Services\SubjectService;
use App\YourEdu\Subject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    //

    public function subjectCreate(SubjectCreateRequest $request)
    {
        try {
            DB::beginTransaction();

            $subject = (new SubjectService())->subjectCreate($request->account,
                $request->accountId,$request->name,$request->description,
                $request->rationale,json_decode($request->aliases));

            DB::commit();
            return response()->json([
                'message' => "successful",
                'subject' => new SubjectResource($subject)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            // return response()->json([
            //     'message' => "unsuccessful, something happened."
            // ],422);
        }

    }

    public function subjectAliasCreate(SubjectAliasCreateRequest $request,$subject)
    {
        try {
            DB::beginTransaction();
            
            $mainSubject = (new SubjectService())->subjectAliasCreate($subject,
                $request->account,$request->accountId,$request->name,$request->description);

            DB::commit();
            return response()->json([
                'message' => "successful",
                'subject' => new SubjectResource($mainSubject)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            // return response()->json([
            //     'message' => "unsuccessful, something happened."
            // ],422);
        }

    }

    public function subjectsGet()
    {
        return [
            'message' => 'successful',
            'subjects' => SubjectResource::collection(Subject::all())
        ];
    }

    public function subjectsSearch($search)
    {
        $subjects = Subject::where('name','like',"%{$search}%")
            ->orWhereHas('aliases',function(Builder $query) use ($search){
                $query->where('name','like',"%{$search}%");
            })->get();

        return response()->json([
            'message' => 'successful',
            'subjects' => SubjectResource::collection($subjects)
        ]);
    }

    public function subjectsDelete($subject)
    {
        try {
            $subjectInfo = (new SubjectService())->subjectDelete($subject,auth()->id());

            return response()->json([
                'message' => $subjectInfo
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
