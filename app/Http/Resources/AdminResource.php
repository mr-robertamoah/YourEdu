<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
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
            'userId' => $this->user_id,
            'username' => $this->user->username,
            'level' => $this->level,
            'name' => $this->name,
            'title' => $this->title,
            'description' => $this->description,
            'state' => $this->state,
            'role' => $this->role,
        ];
    }
}
