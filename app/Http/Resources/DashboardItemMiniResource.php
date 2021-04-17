<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardItemMiniResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (is_array($this->resource)) return $this->resource;

        $data = [];
        $data['type'] =  class_basename_lower($this->resource);
        $data['id'] = $this->id;

        if ($data['type'] === 'class') {      
            $data['name'] = $this->name;
            $data['description'] = $this->description;
            $data['maxLearners'] = $this->max_learners;
            $data['state'] = $this->state;
            $data['lessons'] = $this->lessons()->count();
            $data['learners'] = $this->learners()->count();
            $data['structure'] = $this->structure;
            if ($this->structure === 'courses') {
                $data['items'] = DashboardItemMiniResource::collection($this->courses);
            } else {
                $data['items'] = DashboardItemMiniResource::collection(
                    $this->subjects()->wherePivot('activity','OFFER')->get()
                );
            }
        } else if ($data['type'] === 'school') {    
            $data['name'] = $this->company_name;
            $data['classes'] = $this->ownedClasses()->count();
            $data['academicYearSection'] = $this->academicYearSections;
            $data['about'] = $this->about;
            $data['courses'] = $this->courses()->count();
            $data['role'] = $this->role;
            $data['types'] = $this->types;
            $data['learners'] = $this->learners()->count();
            $data['professionals'] = $this->professionals()->count();
            $data['facilitators'] = $this->facilitators()->count();
        } else if ($data['type'] === 'academicYear') {       
            $data['name'] = $this->name;
            $data['description'] = $this->description;
            $data['endDate'] = $this->end_date;
            $data['startDate'] = $this->start_date;
            $data['sections'] = AcademicYearSectionResource::collection($this->academicYearSections);
        } else if ($data['type'] === 'subject') {    
            $data['name'] = $this->name;
            
            if ($this->subjectClasses()->count()) {

                $data['className'] = $this->subjectClasses[0]->name;
                $data['classId'] = $this->subjectClasses[0]->id;
            }
            $data['description'] = $this->description;
            $data['rationale'] = $this->rationale;
        } else if ($data['type'] === 'course') {    
            $data['name'] = $this->name;
            $data['description'] = $this->description;
            $data['items'] = DashboardItemMiniResource::collection($this->courseSections);
            $data['lessons'] = $this->lessons()->count();
            $data['learners'] = $this->learners()->count();
        } else if ($data['type'] === 'extracurriculum') {    
            $data['name'] = $this->name;
            $data['description'] = $this->description;
            $data['lessons'] = $this->lessons()->count();
            $data['learners'] = $this->learners()->count();
        } else if ($data['type'] === 'assessment') {    
            $data['name'] = $this->name;
            $data['description'] = $this->description;
            $data['assessmentSectionsCount'] = $this->asssessmentSections()->count();
            $data['addedby'] = new UserAccountResource($this->addedby);
        } else if ($data['type'] === 'courseSection') {    
            $data['name'] = $this->name;
            $data['courseId'] = $this->course_id;
            $data['courseName'] = $this->course->name;
            $data['description'] = $this->description;
            $data['lessons'] = $this->lessons()->count();
        } else if ($data['type'] === 'program') {    
            $data['name'] = $this->name;
            $data['description'] = $this->description;
            $data['courses'] = $this->courses()->count();
            $data['learners'] = $this->learners()->count();
            $data['state'] = $this->state;
        } else if ($data['type'] === 'lesson') {    
            $data['title'] = $this->title;
            $data['description'] = $this->description;
            $data['state'] = $this->state;
        } else if ($data['type'] === 'link') {    
            $data['name'] = $this->name;
            $data['description'] = $this->description;
            $data['link'] = $this->link;
        }

        $data['createdAt'] = $this->created_at;
        $data['updatedAt'] = $this->updated_at;

        return $data;
    }
}
