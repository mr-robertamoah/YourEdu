<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAndProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [];
        if ($this->profileable_id) {
            $data['account_id'] = $this->profileable_id;
            $data['account_type'] = class_basename_lower($this->profileable_type);
            $data['profile_name'] = $this->name;
            $data['profile_url'] = $this->url;
            $data['profile'] = $this->profileable_type;
            $data['userId'] = $this->profileable ? $this->profileable->user_id :
                $this->user_id;
        } else {
            $data['username'] = $this->username;
            $data['name'] = $this->name;
        }
        
        return $data;
    }
}
