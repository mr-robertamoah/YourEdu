<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MarkedWorkResource extends JsonResource
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
            'answers' => MarkedAnswerResource::collection($this->getAnswersAnsweredbyUser($request->userId)),
            'status' => strtolower($this->status),
            'id' => $this->id,
            'addedby' => new UserAccountResource($this->addedby),
        ];
    }
}
