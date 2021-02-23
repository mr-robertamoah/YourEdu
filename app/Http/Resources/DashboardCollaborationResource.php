<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardCollaborationResource extends JsonResource
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
            'type' => class_basename_lower($this->resource::class),
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'collaborators' => CollaboratorResource::newCollection($this->collaborators(), $this->resource),
            'addedby' => new UserAccountResource($this->addedby)
        ];
    }
}
