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
        return [
            'accountId' => $this->id,
            'userId' => $this->user_id ? $this->user_id : $this->owner_id,
            'name' => $this->profile ? $this->profile->name : $this->name,
            'url' => $this->profile ? $this->profile->url : '',
            'account' => getAccountString(get_class($this->resource)),
        ];
    }
}
