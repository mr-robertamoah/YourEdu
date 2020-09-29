<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConversationAccountResource extends JsonResource
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
            'state' => $this->state,
            'name' => $this->accountable->profile->name,
            'url' => $this->accountable->profile->url,
            'accountId' => $this->accountable_id,
            'account' => getAccountString($this->accountable_type),
            'user_id' => $this->user_id,
            'conversation_id' => $this->conversation_id,
        ];
    }
}
