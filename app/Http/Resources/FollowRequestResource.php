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
        $data = [];
        $data = [
            'id' => $this->id,
            'requestable_id' => $this->requestable_id,
            'name' => $this->requestfrom->name,
            'url' => $this->requestfrom->profile->url,
        ];

        if (Str::contains(strtolower($this->requestfrom),'learner')) {
            $data['params'] = [
                'account' => 'learner',
                'accountId' => $this->requestfrom_id,
            ];
        } else if (Str::contains(strtolower($this->requestfrom),'facilitator')) {
            $data['params'] = [
                'account' => 'facilitator',
                'accountId' => $this->requestfrom_id,
            ];
        } else if (Str::contains(strtolower($this->requestfrom),'parent')) {
            $data['params'] = [
                'account' => 'parent',
                'accountId' => $this->requestfrom_id,
            ];
        } else if (Str::contains(strtolower($this->requestfrom),'professional')) {
            $data['params'] = [
                'account' => 'professional',
                'accountId' => $this->requestfrom_id,
            ];
        } else if (Str::contains(strtolower($this->requestfrom),'school')) {
            $data['params'] = [
                'account' => 'school',
                'accountId' => $this->requestfrom_id,
            ];
        }

        return $data;
    }
}
