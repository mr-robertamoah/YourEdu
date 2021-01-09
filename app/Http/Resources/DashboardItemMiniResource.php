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
        $data = [];
        $data['type'] = getAccountString($this->resource);
        $data['id'] = $this->id;

        if ($data['type'] === 'class') {      
            $data['name'] = $this->name;
            $data['ownedby'] = new UserAccountResource($this->ownedby);
            $data['description'] = $this->description;
            $data['maxLearners'] = $this->max_learners;
            $data['state'] = $this->state;
            $data['curricula'] = $this->curricula;
            $data['grades'] = GradeResource::collection($this->grades);
            $data['lessons'] = count($this->lessons);
            $data['learners'] = count($this->learners);
            $data['academicYear'] = DashboardAcademicYearResource::collection($this->academicYear);
            $data['subjects'] = count($this->subjects);
            $data['courses'] = count($this->courses);
        } else if ($data['type'] === 'school') {    
            $data['name'] = $this->company_name;
            $data['classes'] = count($this->ownedClasses);
            $data['academicYearSection'] = $this->academicYearSections;
            $data['about'] = $this->about;
            $data['courses'] = count($this->courses);
            $data['role'] = $this->role;
            $data['types'] = $this->types;
            $data['learners'] = count($this->learners);
            $data['professionals'] = count($this->professionals);
            $data['facilitators'] = count($this->facilitators);
        } else if ($data['type'] === 'academicYear') {       
            $data['name'] = $this->name;
            $data['description'] = $this->description;
            $data['endDate'] = $this->end_date;
            $data['startDate'] = $this->start_date;
            $data['sections'] = AcademicYearSectionResource::collection($this->academicYearSections);
        }

        $data['createdAt'] = $this->created_at;
        $data['updatedAt'] = $this->updated_at;

        return $data;
    }
}
