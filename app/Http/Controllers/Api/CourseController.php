<?php

namespace App\Http\Controllers\Api;

use App\DTOs\CourseData;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseCreateRequest;
use App\Http\Requests\CreateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\DashboardCourseResource;
use App\Http\Resources\DashboardItemResource;
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
        return CourseResource::collection(Course::hasNoOwner()->paginate(2));
    }

    public function coursesSearch($search)
    {
        $courses = Course::where('name','like',"%{$search}%")
            ->orWhereHas('aliases',function(Builder $query) use ($search){
                $query->where('name','like',"%{$search}%");
            })->hasNoOwner()->get();

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

    // for actual courses with lessons
    public function createCourse(CreateCourseRequest $request)
    {
        try {
            DB::beginTransaction();
            $course = (new CourseService())->createCourse(
                CourseData::createFromRequest($request)
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'course' => new DashboardCourseResource($course)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function getCourse(Request $request)
    {
        try {
            $course = (new CourseService())->getCourse(
                $request->courseId
            );

            return new CourseResource($course);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getCourses(Request $request)
    {
        try {
            $courses = (new CourseService())->getCourses();

            return CourseResource::collection($courses);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateCourse(Request $request)
    {
        try {
            DB::beginTransaction();
            $course = (new CourseService())->updateCourse(
                CourseData::createFromRequest($request)
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'course' => new DashboardCourseResource($course),
                'courseResource' => json_decode($request->main) ?
                    new DashboardItemResource($course) : null,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function deleteCourse(Request $request)
    {
        try {
            DB::beginTransaction();
            $course = (new CourseService())->deleteCourse(
                CourseData::createFromRequest($request)
            );

            DB::commit();
            return response()->json([
                'message' => 'successful',
                'course' => $course ? new DashboardCourseResource($course) : null
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
