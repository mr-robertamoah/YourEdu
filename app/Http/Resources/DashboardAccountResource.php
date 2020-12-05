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
        $data = [];
        $data['account'] = getAccountString(get_class($this->resource));
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
                $data['classes'] = DashboardClassResource::collection($this->classes);
                $data['curricula'] = DashboardCurriculumResource::collection($this->curricula);
            } else if ($data['account'] === 'parent') {
                $data['wards'] = WardResource::collection($this->wards);
            } else if ($data['account'] === 'facilitator') {
                $data['programs'] = DashboardProgramResource::collection($this->programs);
                $data['curricula'] = DashboardCurriculumResource::collection($this->curricula);
                $data['classes'] = DashboardClassResource::collection($this->classes);
                $data['ownedClasses'] = DashboardClassResource::collection($this->ownedClasses);
                $data['schools'] = DashboardSchoolResource::collection($this->schools);
            } else if ($data['account'] === 'professional') {
                $data['programs'] = DashboardProgramResource::collection($this->programs);
                
            } else if ($data['account'] === 'school') {
                $data['learners'] = UserAccountResource::collection($this->learners);
                $data['parents'] = UserAccountResource::collection($this->parents);
                $data['facilitators'] = UserAccountResource::collection($this->facilitators);
                $data['programs'] = DashboardProgramResource::collection($this->programs);
                $data['curricula'] = DashboardCurriculumResource::collection($this->curricula);
                $data['ownedClasses'] = DashboardClassResource::collection($this->ownedClasses);
                $data['academicYears'] = AcademicYearResource::collection($this->academicYears);
                $data['classStructure'] = $this->class_structure;
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
