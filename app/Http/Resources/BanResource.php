<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BanResource extends JsonResource
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
            'adminId' => $this->admin_id,
            'state' => $this->state,
            'type' => $this->type,
            'dueDate' => $this->due_date,
            'createdAt' => $this->created_at,
        ];
    }
}
