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
        $data['account'] = class_basename_lower($class);
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
                    $query->whereHasMorph('addedby','*',function($q,$type){
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
                $data['programs'] = ProgramResource::collection(
                    $this->programs()->hasOwner()->get()->sortByDesc('created_at')
                );
                $data['schools'] = DashboardSchoolResource::collection(
                    $this->schools->sortByDesc('created_at')
                );
                $data['courses'] = CourseResource::collection(
                    $this->courses()->hasOwner()->get()->sortByDesc('created_at')
                );
                $data['classes'] = DashboardClassResource::collection(
                    $this->classes->sortByDesc('created_at')
                );
                $data['extracurriculums'] = ExtracurriculumResource::collection(
                    $this->extracurriculums->sortByDesc('created_at')
                );
            } else if ($data['account'] === 'parent') {
                $data['wards'] = WardResource::collection($this->wards);
                $data['courses'] = CourseResource::collection(
                    $this->courses()
                        ->notOwnedby($class,$data['accountId'])->get()->sortByDesc('created_at')
                );
                $data['schools'] = DashboardSchoolResource::collection(
                    $this->schools->sortByDesc('created_at')
                );
            } else if ($data['account'] === 'facilitator') {
                $data['hasFreeResources'] = $this->hasFreeResources();
                $data['programs'] = DashboardProgramResource::collection(
                    $this->programs()
                        ->notOwnedby($class,$data['accountId'])->get()->sortByDesc('created_at')
                );
                $data['subjects'] = DashboardAttachmentResource::collection($this->subjects);
                $data['programs'] = DashboardProgramResource::collection(
                    $this->programs->sortByDesc('created_at')
                );
                $data['ownedPrograms'] = DashboardProgramResource::collection(
                    $this->ownedPrograms->sortByDesc('created_at')
                );
                $data['curricula'] = DashboardCurriculumResource::collection($this->curricula);
                $data['classes'] = DashboardClassResource::collection(
                    $this->classes()
                        ->notOwnedby($class,$data['accountId'])->get()->sortByDesc('created_at')
                );
                $data['ownedClasses'] = DashboardClassResource::collection(
                    $this->ownedClasses->sortByDesc('created_at')
                );
                $data['courses'] = CourseResource::collection(
                    $this->courses()
                        ->notOwnedby($class,$data['accountId'])->get()->sortByDesc('created_at')
                );
                $data['ownedCourses'] = DashboardCourseResource::collection(
                    $this->ownedCourses->sortByDesc('created_at')
                );
                $data['schools'] = DashboardSchoolResource::collection(
                    $this->schools->sortByDesc('created_at')
                );
                $data['lessons'] = $this->addedLessons;
                $data['lessons'] = $data['lessons']->merge($this->ownedLessons);
                $data['lessons'] = DashboardLessonResource::collection(
                    $data['lessons']->unique()->sortByDesc('created_at')
                );
                $data['ownedExtracurriculums'] = DashboardExtracurriculumResource::collection(
                    $this->ownedExtracurriculums->sortByDesc('created_at')
                );
                $data['extracurriculums'] = ExtracurriculumResource::collection(
                    $this->extracurriculums->sortByDesc('created_at')
                );
                $data['collaborations'] = DashboardCollaborationResource::collection(
                    $this->allCollaborations()
                );
            } else if ($data['account'] === 'professional') {
                $data['hasFreeResources'] = $this->hasFreeResources();
                $data['courses'] = CourseResource::collection(
                    $this->courses()
                        ->notOwnedby($class,$data['accountId'])->get()->sortByDesc('created_at')
                    );
                $data['programs'] = DashboardProgramResource::collection(
                    $this->programs->sortByDesc('created_at')
                );
                $data['ownedCourses'] = DashboardCourseResource::collection(
                    $this->ownedCourses->sortByDesc('created_at')
                );
                $data['programs'] = DashboardProgramResource::collection(
                    $this->programs()
                        ->notOwnedby($class,$data['accountId'])->get()->sortByDesc('created_at')
                );
                $data['ownedPrograms'] = DashboardProgramResource::collection(
                    $this->ownedPrograms->sortByDesc('created_at')
                );
                $data['extracurriculums'] = ExtracurriculumResource::collection(
                    $this->extracurriculums->sortByDesc('created_at')
                );
                $data['schools'] = DashboardSchoolResource::collection(
                    $this->schools->sortByDesc('created_at')
                );
                $data['lessons'] = $this->addedLessons;
                $data['lessons'] = $data['lessons']->merge($this->ownedLessons);
                $data['lessons'] = DashboardLessonResource::collection(
                    $data['lessons']->unique()->sortByDesc('created_at')
                );
                $data['collaborations'] = DashboardCollaborationResource::collection(
                    $this->allCollaborations()
                );
            } else if ($data['account'] === 'school') {
                $data['hasFreeResources'] = $this->hasFreeResources();
                $data['learners'] = UserAccountResource::collection($this->learners);
                $data['parents'] = UserAccountResource::collection($this->parents);
                $data['facilitators'] = UserAccountResource::collection($this->facilitators);
                $data['ownedPrograms'] = DashboardProgramResource::collection(
                    $this->ownedPrograms->sortByDesc('created_at')
                );
                $data['curricula'] = DashboardCurriculumResource::collection($this->curricula);
                $data['ownedCourses'] = DashboardCourseResource::collection(
                    $this->ownedCourses->sortByDesc('created_at')
                );
                $data['ownedClasses'] = DashboardClassResource::collection(
                    $this->ownedClasses->sortByDesc('created_at')
                );
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
                $data['collaborations'] = DashboardCollaborationResource::collection(
                    $this->allCollaborations()
                );
            }
        }

        return $data;
    }
}
