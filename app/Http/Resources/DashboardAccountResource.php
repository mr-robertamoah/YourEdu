<?php

namespace App\Http\Resources;

use App\YourEdu\Admin;
use App\YourEdu\Like;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $class = get_class($this->resource);
        $data = [];
        $data['account'] = getAccountString($class);
        $data['accountId'] = $this->id;
        if ($data['account'] === 'admin') {
            $data['name'] = $this->name;
            $data['activities'] = $this->activityTracks;
            $data['role'] = $this->role;
        } else {
            $data['name'] = $this->profile->name;
            $data['url'] = $this->profile->url;
            $data['posts_count'] = $this->posts->count();
            $data['discussions_count'] = $this->discussions->count();
            $data['points'] = $this->points ? $this->points->value : 0;
            $data['likes'] = Like::whereHasMorph('likeable','*',function($query,$type){
                if ($type === 'App\YourEdu\Post') {
                    $query->whereHasMorph('postedby','*',function($q,$type){
                        if ($type === 'App\YourEdu\School') {
                            $q->where('owner_id',$this->profile->user_id);
                        } else {
                            $q->where('user_id',$this->profile->user_id);
                        }
                    });
                } else if ($type === 'App\YourEdu\Discussion') {
                    $query->whereHasMorph('raisedby','*',function($q,$type){
                        if ($type === 'App\YourEdu\School') {
                            $q->where('owner_id',$this->profile->user_id);
                        } else {
                            $q->where('user_id',$this->profile->user_id);
                        }
                    });
                } else if ($type === 'App\YourEdu\Comment') {
                    $query->whereHasMorph('commentedby','*',function($q,$type){
                        if ($type === 'App\YourEdu\School') {
                            $q->where('owner_id',$this->profile->user_id);
                        } else {
                            $q->where('user_id',$this->profile->user_id);
                        }
                    });
                } else if ($type === 'App\YourEdu\Answer') {
                    $query->whereHasMorph('answeredby','*',function($q,$type){
                        if ($type === 'App\YourEdu\School') {
                            $q->where('owner_id',$this->profile->user_id);
                        } else {
                            $q->where('user_id',$this->profile->user_id);
                        }
                    });
                }
            })->count();
            $data['courses'] = DashboardCourseResource::collection($this->courses);
            if ($data['account'] === 'learner') {
                $data['parents'] = ParentResource::collection($this->parents);
                $data['programs'] = DashboardProgramResource::collection($this->programs);
                $data['schools'] = DashboardSchoolResource::collection($this->schools);
                $data['courses'] = DashboardCourseResource::collection($this->courses);
                $data['classes'] = DashboardClassResource::collection($this->classes);
                $data['extracurriculums'] = DashboardExtracurriculumResource::collection($this->extracurriculums);
            } else if ($data['account'] === 'parent') {
                $data['wards'] = WardResource::collection($this->wards);
                $data['courses'] = DashboardCourseResource::collection(
                    $this->courses()
                        ->notOwnedby($class,$data['accountId'])->get());
                $data['schools'] = DashboardSchoolResource::collection($this->schools);
            } else if ($data['account'] === 'facilitator') {
                $data['programs'] = DashboardProgramResource::collection(
                    $this->programs()
                        ->notOwnedby($class,$data['accountId'])->get());
                $data['subjects'] = DashboardAttachmentResource::collection($this->subjects);
                $data['ownedPrograms'] = DashboardProgramResource::collection($this->ownedPrograms);
                $data['curricula'] = DashboardCurriculumResource::collection($this->curricula);
                $data['classes'] = DashboardClassResource::collection(
                    $this->classes()
                        ->notOwnedby($class,$data['accountId'])->get());
                $data['ownedClasses'] = DashboardClassResource::collection($this->ownedClasses);
                $data['courses'] = DashboardCourseResource::collection(
                    $this->courses()
                        ->notOwnedby($class,$data['accountId'])->get());
                $data['ownedCourses'] = DashboardCourseResource::collection($this->ownedCourses);
                $data['schools'] = DashboardSchoolResource::collection($this->schools);
                $data['ownedExtracurriculums'] = DashboardExtracurriculumResource::collection($this->ownedExtracurriculums);
            } else if ($data['account'] === 'professional') {
                $data['courses'] = DashboardCourseResource::collection(
                    $this->courses()
                        ->notOwnedby($class,$data['accountId'])->get());
                $data['ownedCourses'] = DashboardCourseResource::collection($this->ownedCourses);
                $data['programs'] = DashboardProgramResource::collection(
                    $this->programs()
                        ->notOwnedby($class,$data['accountId'])->get());
                $data['ownedPrograms'] = DashboardProgramResource::collection($this->ownedPrograms);
                $data['extracurriculums'] = DashboardExtracurriculumResource::collection($this->extracurriculums);
                $data['schools'] = DashboardSchoolResource::collection($this->schools);
            } else if ($data['account'] === 'school') {
                $data['learners'] = UserAccountResource::collection($this->learners);
                $data['parents'] = UserAccountResource::collection($this->parents);
                $data['facilitators'] = UserAccountResource::collection($this->facilitators);
                $data['ownedPrograms'] = DashboardProgramResource::collection($this->ownedPrograms);
                $data['curricula'] = DashboardCurriculumResource::collection($this->curricula);
                $data['ownedCourses'] = DashboardCourseResource::collection($this->ownedCourses);
                $data['ownedClasses'] = DashboardClassResource::collection($this->ownedClasses);
                $data['academicYears'] = AcademicYearResource::collection($this->currentAcademicYears);
                $data['ownedExtracurriculums'] = DashboardExtracurriculumResource::collection($this->ownedExtracurriculums);
                $data['classStructure'] = $this->class_structure;
                $data['types'] = $this->types;
                $data['about'] = $this->about;
                $id = auth()->id();
                $data['owner'] = $this->owner_id === $id;
                if ($data['owner']) {
                    $data['admins'] = AdminResource::collection($this->admins);
                } else {
                    $data['admin'] = new AdminResource($this->admins()->where('user_id',$id)->first());
                }
            }
        }

        return $data;
    }
}
