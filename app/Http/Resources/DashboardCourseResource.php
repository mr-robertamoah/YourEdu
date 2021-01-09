<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardCourseResource extends JsonResource
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
            'name' => $this->name,
            'state' => $this->state,
            'programs' => DashboardAttachmentResource::collection($this->programs),
            'courses' => DashboardAttachmentResource::collection($this->courses),
            'grades' => DashboardAttachmentResource::collection($this->grades),
            'description' => $this->description,
            'prices' => PaymentTypeResource::collection($this->prices),
            'subscriptions' => PaymentTypeResource::collection($this->subscriptions),
            'ownedby' => new UserAccountResource($this->ownedby),
            'addedby' => new UserAccountResource($this->addedby),
            'facilitators' => UserAccountResource::collection($this->facilitators),
            'professionals' => UserAccountResource::collection($this->professionals),
            'learners' => UserAccountResource::collection($this->learners),
            'lessons' => $this->lessons->count(),
        ];
    }
}
