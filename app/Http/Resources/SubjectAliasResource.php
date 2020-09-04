<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectAliasResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    { //used for alias
        return  [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
