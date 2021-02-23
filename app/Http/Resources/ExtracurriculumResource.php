<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExtracurriculumResource extends JsonResource
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
            'description' => $this->description,
            'programs' => DashboardAttachmentResource::collection(
                $this->programs()->hasNoOwner()->get()
            ),
            // 'grades' => DashboardAttachmentResource::collection($this->grades),
            // 'courses' => DashboardAttachmentResource::collection($this->courses),
            'state' => $this->state,
            // 'ownedby' => new UserAccountResource($this->ownedby),
            // 'facilitators' => UserAccountResource::collection($this->facilitators),
            // 'prices' => PaymentTypeResource::collection($this->prices),
            // 'subscriptions' => PaymentTypeResource::collection($this->subscriptions),
            'lessons' => $this->lessons->count(),
            'learners' => $this->learners->count(),
            'discussions' => $this->discussions->count(),
        ];
        // $data['classes'] = $this->classes;
        // $data['classes'] = $data['classes']->merge($this->programs()->hasOwner()->get());
        // $data['classes'] = DashboardItemMiniResource::collection($data['classes']);

        return $data;
    }
}
