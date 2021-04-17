<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserMiniResource extends JsonResource
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
            'fullName' => $this->full_name,
            'username' => $this->username,
            'bans' => BanResource::collection($this->pendingAndServedBans()),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'id' => $this->id,
            'gender' => $this->gender,
            'email' => $this->email,
            'profiles' => $this->profiles()->count(),
        ];
    }
}
