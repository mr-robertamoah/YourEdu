<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PossibleAnswerResource extends JsonResource
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
            'id' => $this->id,
            'option' => $this->option,
            'state' => $this->state,
        ];
    }
}
