<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseCreateRequest;
use App\Http\Resources\CourseResource;
use App\Services\CourseService;
use App\YourEdu\Course;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    
    public function courseCreate(CourseCreateRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $course = (new CourseService())->courseCreate($request->account,
                $request->accountId,$request->name,$request->description,
                $request->rationale,json_decode($request->aliases));

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
        try {
            DB::beginTransaction();

            $mainCourse = (new CourseService())->courseAliasCreate($course,
                $request->account,$request->accountId,$request->name,$request->description);

            DB::commit();
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
        try {
            $courseInfo = (new CourseService())->courseDelete($course,auth()->id());

            return response()->json([
                'message' => $courseInfo
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
