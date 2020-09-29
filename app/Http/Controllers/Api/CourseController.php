<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CourseResource;
use App\Services\Attachment;
use App\YourEdu\Course;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Attachment
{
    
    public function courseCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'rationale' => 'nullable|string',
            'aliases' => 'nullable|array',
        ]);
        if ($request->account === 'learner' || $request->account === 'parent') {
            return response()->json([
                'message' => 'unsuccessful, learner or parent can only create an alias of a course'
            ],422);
        }

        try { 

            DB::beginTransaction();
            
            $course = $this->createAttachment($request, 'course');

            if (is_null($course)) {
                return response()->json([
                    'message' => 'unsuccessful'
                ],422);
            }

            DB::commit();
            return response()->json([
                'message' => "successful",
                'course' => new CourseResource($course)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            // return response()->json([
            //     'message' => "unsuccessful, something happened."
            // ],422);
        }

    }

    public function courseAliasCreate(Request $request,$course)
    {
        $mainCourse = Course::find($course);

        if (!$mainCourse) { 
            return response()->json([
                'message' => 'unsuccessful, course does not exist'
            ],422);
        }

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);
        
        $account = getAccountObject($request->account, $request->accountId);

        if (is_null($account)) {
            return response()->json([
                'message' => "unsuccessful, {$request->account} does not exist"
            ],422);
        }

        try {

            DB::beginTransaction();

            $this->createAttachmentAlias($request,$account,$mainCourse);

            DB::commit();
            $mainCourse = Course::find($mainCourse->id);
            return response()->json([
                'message' => "successful",
                'course' => new CourseResource($mainCourse)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            // return response()->json([
            //     'message' => "unsuccessful, something happened."
            // ],422);
        }

    }

    public function coursesGet()
    {
        return [
            'message' => 'successful',
            'courses' => CourseResource::collection(Course::all())
        ];
    }

    public function coursesSearch($search)
    {
        $courses = Course::where('name','like',"%{$search}%")
            ->orWhereHas('aliases',function(Builder $query) use ($search){
                $query->where('name','like',"%{$search}%");
            })->get();

        return response()->json([
            'message' => 'successful',
            'courses' => CourseResource::collection($courses)
        ]);
    }

    public function coursesDelete($course)
    {
        $mainCourse = Course::find($course);
        if (!$mainCourse) {
            return response()->json([
                'message' => 'unsuccessful, course does not exist'
            ],422);
        }

        if ($mainCourse->addedby->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'unsuccessful, you cannot delete course you did not create'
            ],422);
        }

        $mainCourse->delete();

        return response()->json([
            'message' => 'successful'
        ]);
    }
}
