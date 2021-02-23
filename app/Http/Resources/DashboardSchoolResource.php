<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardSchoolResource extends JsonResource
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
            'about' => $this->about,
            'classStructrue' => $this->class_structrue,
            'role' => $this->role,
            'UserId' => $this->owner_id,
            'name' => $this->company_name,
            'hasFreeResources' => $this->hasFreeResources(),
        ];
    }
}
