<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $message = null;
        if (count($this->messages) && $this->messages[0]) {
            $message = $this->messages[0];
        }
        return [
            'id' => $this->id,
            'type' => $this->type,
            'state' => $this->state,
            'accountType' => $this->account_type,
            'description' => $this->description,
            'updatedAt' => $this->updated_at->diffForHumans(),
            'createdAt' => $this->created_at->diffForHumans(),
            'message' => $message,
            'conversationAccounts' => ConversationAccountResource::collection($this->conversationAccounts),
        ];
    }
}
