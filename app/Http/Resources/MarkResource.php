<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MarkResource extends JsonResource
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
            'answer_id' => $this->answer_id,
            'score' => $this->score,
            'score_over' => $this->score_over,
            'remark' => $this->remark,
            'markedby_id' => $this->markedby_id,
            'markedby_type' => $this->markedby_type,
            'markedby_name' => $this->markedby->name,
            'markedby_url' => $this->markedby->profile->url,
            'created_at' => $this->created_at,
        ];

        return $data;
    }
}
