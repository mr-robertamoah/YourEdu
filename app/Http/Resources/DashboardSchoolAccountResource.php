<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardSchoolAccountResource extends JsonResource
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
            'account' => getAccountString($this->resource),
            'userId' => $this->user_id,
            'name' => $this->name,
        ];
    }
}
