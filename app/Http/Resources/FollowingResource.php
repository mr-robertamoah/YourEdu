<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FollowingResource extends JsonResource
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
            'followed_user_id' => $this->followed_user_id,
            'name' => $this->followable->profile->name,
            'my_name' => $this->followedby->profile->name,
            'url' => $this->followable->profile->url,
            'followedby_chat_status' => $this->followedby_chat_status,
            'followable_chat_status' => $this->followable_chat_status,
            'conversation_id' => $this->conversation_id,
            'followable_type' => $this->followable_type,
            'followable_id' => $this->followable_id,
            'followedby_type' => $this->followedby_type,
            'followedby_id' => $this->followedby_id,
        ];
    }
}
