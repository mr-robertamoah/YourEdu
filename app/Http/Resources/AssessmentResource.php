<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'totalMark' => $this->total_mark,
            'duration' => $this->duration,
            'publishedAt' => $this->published_at,
            'dueAt' => $this->due_at,
            'addedby' => new UserAccountResource($this->addedby),
            'assessmentSections' => AssessmentSectionsResource::collection($this->assessmentSections),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'restricted' => $this->restricted,
            'type' => $this->type,
        ];
    }
}
