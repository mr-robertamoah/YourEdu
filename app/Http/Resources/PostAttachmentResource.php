<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostAttachmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'attachedwith_id' => $this->attachedwith_id,
            'attachedwith_type' => $this->attachedwith_type,
            'user_id' => $this->attachedby->user_id,
            'name' => $this->attachedwith->name,
        ];

        return $data;
    }
}
