<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class FollowRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'requestableId' => $this->requestable_id,
            'requestableType' => class_basename_lower($this->requestable_type),
            'name' => $this->requestfrom->name,
            'url' => $this->requestfrom->profile->url,
            'about' => $this->requestfrom->profile->about,
            'account' => $this->requestfrom->accountType,
            'accountId' => $this->requestfrom_id,
        ];

        return $data;
    }
}
