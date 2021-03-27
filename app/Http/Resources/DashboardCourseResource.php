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
        $data =  [
            'id' => $this->id,
            'name' => $this->name,
            'state' => $this->state,
            'standAlone' => $this->stand_alone,
            'programs' => DashboardAttachmentResource::collection(
                $this->programs()->hasNoOwner()->get()
            ),
            'courses' => DashboardAttachmentResource::collection($this->courses),
            'grades' => DashboardAttachmentResource::collection($this->grades),
            'description' => $this->description,
            'prices' => PaymentTypeResource::collection($this->prices),
            'subscriptions' => PaymentTypeResource::collection($this->subscriptions),
            'ownedby' => new UserAccountResource($this->ownedby),
            'addedby' => new UserAccountResource($this->addedby),
            'facilitators' => UserAccountResource::collection($this->facilitators),
            'professionals' => UserAccountResource::collection($this->professionals),
            'learners' => $this->learners->count(),
            'lessons' => $this->lessons->count(),
            'sections' => DashboardItemMiniResource::collection($this->courseSections),
            'discussions' => $this->discussions->count(),
            'items' => DashboardItemMiniResource::collection($this->items())
        ];

        return $data;
    }
}
