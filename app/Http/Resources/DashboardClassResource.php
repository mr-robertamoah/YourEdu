<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardClassResource extends JsonResource
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
            'maxLearners' => $this->max_learners,
            'state' => $this->state,
            'grades' => GradeResource::collection($this->grades),
            'structure' => $this->structure,
            'ownedby' => new UserAccountResource($this->ownedby),
            'facilitators' => UserAccountResource::collection($this->facilitators),
            'fees' => PaymentTypeResource::collection($this->fees),
            // 'courses' => DashboardAttachmentResource::collection($this->courses),
            // 'subjects' => DashboardAttachmentResource::collection($this->subjects),
            'programs' => DashboardAttachmentResource::collection(
                $this->programs()->hasNoOwner()->get()
            ),
            'subscriptions' => PaymentTypeResource::collection($this->subscriptions),
            'prices' => PaymentTypeResource::collection($this->prices),
            'lessons' => $this->lessons->count(),
            'discussions' => $this->discussions->count(),
            'learners' => $this->learners->count(),
            'academicYears' => AcademicYearResource::collection($this->academicYears),
        ];
        $data['items'] = $this->subjects;
        $data['items'] = $data['items']->merge($this->courses()->hasOwner()->get());
        $data['items'] = DashboardItemMiniResource::collection($data['items']);

        return $data;
    }
}
