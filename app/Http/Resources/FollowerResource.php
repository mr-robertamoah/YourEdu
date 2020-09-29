<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FollowerResource extends JsonResource
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
            'user_id' => $this->user_id,
            'followedby_type' => $this->followedby_type,
            'followedby_id' => $this->followedby_id,
            'followedby_chat_status' => $this->followedby_chat_status,
            'followable_chat_status' => $this->followable_chat_status,
            'name' => $this->followedby->profile->name,
            'conversation_id' => $this->conversation_id,
            'my_name' => $this->followable->profile->name,
            'url' => $this->followedby->profile->url,
            'followable_type' => $this->followable_type,
            'followable_id' => $this->followable_id,
        ];
    }
}
