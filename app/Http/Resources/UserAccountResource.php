<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $type = class_basename_lower($this->resource);
        $what = $type === 'profile'
            ? $this->profileable : $this;

        $data = [
            'accountId' => $what->id,
            'userId' => $what->user_id ?: $what->owner_id,
            'username' => $what->username ?: $what->user->username,
            'account' => $type === 'profile' ?
                class_basename_lower($this->profileable) :
                $type,
        ];

        if ($type === 'profile') {
            $data['name'] = $this->name ?: $what->name;
            $data['url'] = $this->url ? $this->url : '';
            return $data;
        }

        $data['name'] = $what->profile ? $what->profile->name : $what->name;
        $data['url'] = $what->profile ? $what->profile->url : '';
        
        return $data;
    }
}
