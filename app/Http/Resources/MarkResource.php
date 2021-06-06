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
            'user_id' => $this->markedby->user_id,
            'score' => $this->score,
            // 'remark' => $this->remark,
            //myt consider adding this later
            // 'markedby_id' => $this->markedby_id, 
            // 'markedby_type' => $this->markedby_type,
            // 'markedby_name' => $this->markedby->name,
            // 'markedby_url' => $this->markedby->profile->url,
        ];

        return $data;
    }
}
