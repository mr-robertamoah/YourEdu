<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BanBroadcastResource extends JsonResource
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
            'account' => class_basename_lower($this->bannable_type),
            'username' => $this->bannable->username,
            // 'name' => $this->bannable->name,
            'state' => $this->state,
            'type' => $this->type,
            'dueDate' => $this->due_date,
            'accountId' => $this->bannable_id,
        ];
    }
}
