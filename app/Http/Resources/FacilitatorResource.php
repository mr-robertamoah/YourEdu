<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FacilitatorResource extends JsonResource
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
            // 'user_id' => $this->user_id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'phoneNumbers' => $this->when(
                $this->phoneNumbers()->exists()&& 
                $this->phoneNumbers()->count() > 3,
                $this->phoneNumbers()->take(3),
                $this->phoneNumbers
            ),
            'emails' => $this->when(
                $this->emails()->exists()&& 
                $this->emails()->count() > 3,
                $this->emails()->take(3),
                $this->emails
            ),
            'verification' => $this->verification,
            'subjects' => $this->when(
                $this->subjects()->exists() && 
                $this->subjects()->count() > 3,
                $this->subjects()->take(3),
                $this->subjects
            ),
            'grades' => $this->when(
                $this->grades()->exists() && 
                $this->grades()->count() > 3 &&
                $this->grades()->take(3),
                $this->grades
            ),
            'schools' => $this->when(
                $this->schools()->exists() && 
                $this->schools()->count() > 3,
                $this->schools()->take(3),
                $this->groups
            ),
            'groups' => $this->when(
                $this->groups()->exists() && 
                $this->groups()->count() > 3 &&
                $this->groups()->take(3),
                $this->groups
            ),
            'classes' => $this->when(
                $this->classes()->exists() && 
                $this->classes()->count() > 3 &&
                $this->classes()->take(3),
                $this->classes
            ),
            'curricula' => $this->when(
                $this->curricula()->exists() && 
                $this->curricula()->count() > 3 &&
                $this->curricula()->take(3),
                $this->curricula
            ),
            'extracurriculums' => $this->when(
                $this->extracurriculums()->exists() && 
                $this->extracurriculums()->count() > 3 &&
                $this->extracurriculums()->take(3),
                $this->extracurriculums
            ),
        ];
    }
}
