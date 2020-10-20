<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RemarkResource extends JsonResource
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
            'user_id' => $this->user_id,
            'score' => $this->score,
            'score_over' => $this->score_over,
            'remark' => $this->remark,
            //myt consider adding this later
            'markedby_id' => $this->markedby_id, 
            'markedby_type' => $this->markedby_type,
            'markedby_name' => $this->markedby->name,
            // 'markedby_url' => $this->markedby->profile->url,
        ];
    }
}
