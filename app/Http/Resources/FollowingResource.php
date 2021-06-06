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
            'followedby_chat_status' => $this->followedby_chat_status,
            'followable_chat_status' => $this->followable_chat_status,
            'conversation_id' => $this->conversation_id,
            'myAccount' => $this->followedby_type,
            'myAccountId' => $this->followedby_id,
            'otherAccount' => new UserAccountResource($this->followable),
        ];
    }
}
