<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardItemResource extends JsonResource
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
            $data['addedby'] = new UserAccountResource($this->addedby);
            $data['ownedby'] = new UserAccountResource($this->ownedby);
            $data['description'] = $this->description;
            $data['maxLearners'] = $this->max_learners;
            $data['state'] = $this->state;
            $data['curricula'] = $this->curricula;
            $data['grades'] = GradeResource::collection($this->grades);
            $data['lessons'] = LessonResource::collection($this->lessons);
            $data['facilitators'] = $this->facilitators;
            $data['learners'] = $this->learners;
            $data['sections'] = $this->sections;
            $data['collaboration'] = $this->collaboration;
            $data['academicYear'] = $this->academicYear;
            $data['discussions'] = $this->discussions;
            $data['fees'] = $this->fees;
            $data['subjects'] = SubjectResource::collection($this->subjects);
            $data['extracurriculums'] = $this->extracurriculums;
        }


        return $data;
    }
}
