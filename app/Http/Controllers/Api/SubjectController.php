<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SubjectResource;
use App\Services\Attachment;
use App\YourEdu\Subject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Attachment
{
    //

    public function subjectCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'rationale' => 'nullable|string',
            'aliases' => 'nullable|array',
        ]);
        if ($request->account === 'learner' || $request->account === 'parent') {
            return response()->json([
                'message' => 'unsuccessful, learner or parent can only create an alias of a subject'
            ],422);
        }

        try { 

            DB::beginTransaction();
            $subject = $this->createAttachment($request, 'subject');

            if (!$subject) {
                return response()->json([
                    'message' => 'unsuccessful, subject was not created'
                ],422);
            }

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

    public function subjectAliasCreate(Request $request,$subject)
    {
        $mainSubject = Subject::find($subject);

        if (!$mainSubject) {
            return response()->json([
                'message' => 'unsuccessful, subject does not exist'
            ],422);
        }

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {

            DB::beginTransaction();
            $alias = $this->createAttachmentAlias($request,$mainSubject);

            if (!$alias) {
                return response()->json([
                    'message' => 'unsuccessful, alias was not created'
                ],422);
            }

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
        $mainSubject = Subject::find($subject);
        if (!$mainSubject) {
            return response()->json([
                'message' => 'unsuccessful, subject does not exist'
            ],422);
        }

        if ($mainSubject->addedby->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'unsuccessful, you cannot delete subject you did not create'
            ],422);
        }

        $mainSubject->delete();

        return response()->json([
            'message' => 'successful'
        ]);
    }
}
