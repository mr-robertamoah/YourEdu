<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WardResource extends JsonResource
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
            'role' => $this->pivot->role,
            'level' => $this->pivot->level,
            'parentingCreatedAt' => $this->pivot->created_at,
            'accountId' => $this->id,
            'userId' => $this->user_id,
            'name' => $this->profile->name,
            'url' => $this->profile->url,
            'account' => class_basename_lower(get_class($this->resource)),
        ];
    }
}
