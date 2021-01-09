<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardUserResource extends JsonResource
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
            'full_name' => $this->full_name,
            'username' => $this->username,
            'secret_answer' => $this->secret_answer,
            'dob' => $this->dob,
            'age' => $this->age,
            'bans' => BanResource::collection($this->hasBan()->get()),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'other_names' => $this->other_names,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_superadmin' => $this->is_superadmin,
            'is_supervisoradmin' => $this->is_supervisoradmin,
            'id' => $this->id,
            'gender' => $this->gender,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'profiles' => OwnedProfileResource::collection($this->profiles),
        ];
    }
}
