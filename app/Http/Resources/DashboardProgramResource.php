<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardProgramResource extends JsonResource
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
            'name' => $this->name,
            'state' => $this->state,
            'description' => $this->description,
            'courses' => $this->courses->count(),
            'discussions' => $this->discussions->count(),
            'learners' => $this->learners->count(),
            'grades' => DashboardAttachmentResource::collection($this->grades),
            'courses' => DashboardAttachmentResource::collection(
                $this->courses()->hasNoOwner()->get()
            ),
            'programs' => DashboardAttachmentResource::collection(
                $this->programs()->hasNoOwner()->get()
            ),
            'ownedby' => new UserAccountResource($this->ownedby),
            'facilitators' => UserAccountResource::collection($this->facilitators),
            'professionals' => UserAccountResource::collection($this->professionals),
            'prices' => PaymentTypeResource::collection($this->prices),
            'subscriptions' => PaymentTypeResource::collection($this->subscriptions),
        ];
        $courses = $this->courses()->withCount('lessons')->hasOwner()->get();
        $data['items'] = $this->extracurriculums;
        $data['items'] = $data['items']->merge($courses);
        $data['items'] = DashboardItemMiniResource::collection($data['items']);

        return $data;
    }
}
