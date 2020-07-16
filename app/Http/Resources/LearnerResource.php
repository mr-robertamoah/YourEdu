<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LearnerResource extends JsonResource
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
            'user_id' => $this->user_id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'phoneNumbers' => $this->when(
                $this->phoneNumbers()->exists()&& 
                $this->phoneNumbers()->count() > 3,
                $this->phoneNumbers()->take(3),
                $this->phoneNumbers
            ),
            'parents' => $this->when(
                $this->parents()->exists(),
                $this->parents,
                null
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
            )
        ];
    }
}
