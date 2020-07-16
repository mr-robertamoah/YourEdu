<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParentModelResource extends JsonResource
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
            'learners_number' => $this->learners()->count(),
            'phoneNumbers' => $this->when(
                $this->phoneNumbers()->exists()&& 
                $this->phoneNumbers()->count() > 3,
                $this->phoneNumbers()->take(3),
                $this->phoneNumbers
            ),
            'verification' => $this->verification,
            'learners' => $this->when(
                $this->learners()->exists() && 
                $this->learners()->count() > 3,
                $this->learners()->take(3),
                $this->learners
            ),
            'groups' => $this->when(
                $this->groups()->exists() && 
                $this->groups()->count() > 3 &&
                $this->groups()->take(3),
                $this->groups
            ),
        ];
    }
}
