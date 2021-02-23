<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardAccountItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [];
        $data['type'] =  class_basename_lower($this->resource);
        $data['id'] = $this->id;
        
        if ($data['type'] === 'lesson') {
            $data['title'] = $this->title;
            $data['state'] = $this->state;
            $data['ownedby'] = new UserAccountResource($this->ownedby);
            $data['addedby'] = new UserAccountResource($this->addedby);
            $data['description'] = $this->description;
            $data['links'] = DashboardItemMiniResource::collection($this->links);
            $data['prices'] = PaymentTypeResource::collection($this->prices);
            $data['discussions'] = DiscussionResource::collection($this->discussions);
            $data['images'] = ImageResource::collection($this->images);
            $data['videos'] = VideoResource::collection($this->videos);
            $data['audios'] = AudioResource::collection($this->audios);
            $data['files'] = FileResource::collection($this->files);
            $data['items'] = $this->classSubjects();
            $data['items'] = $data['items']->merge($this->courseSections);
            $data['items'] = $data['items']->merge($this->courses()->hasOwner()->get());
            $data['items'] = DashboardItemMiniResource::collection($data['items']);
            $data['attachments'] = $this->programs()->hasNoOwner()->get();
            $data['attachments'] = $data['attachments']->merge($this->grades);
            $data['attachments'] = $data['attachments']->merge($this->subjects);
            $data['attachments'] = $data['attachments']->merge($this->courses()->hasNoOwner()->get());
            $data['attachments'] = DashboardItemMiniResource::collection($data['attachments']);
        } else if ($data['type'] === 'class') {
            $data['id'] = $this->id;
            $data['name'] = $this->name;
            $data['description'] = $this->description;
            $data['maxLearners'] = $this->max_learners;
            $data['state'] = $this->state;
            $data['grades'] = GradeResource::collection($this->grades);
            $data['structure'] = $this->structure;
            $data['ownedby'] = new UserAccountResource($this->ownedby);
            $data['facilitators'] = UserAccountResource::collection($this->facilitators);
            $data['fees'] = PaymentTypeResource::collection($this->fees);
            $data['prices'] = PaymentTypeResource::collection($this->prices);
            $data['subscriptions'] = PaymentTypeResource::collection($this->subscriptions);
            $data['lessons'] = $this->lessons->count();
            $data['learners'] = $this->learners->count();
            $data['discussions'] = $this->discussions->count();
            $data['academicYears'] = AcademicYearResource::collection($this->academicYears);
            $data['programs'] = DashboardAttachmentResource::collection(
                $this->programs()->hasNoOwner()->get()
            );
            $data['items'] = $this->subjects;
            $data['items'] = $data['items']->merge($this->courses()->hasOwner()->get());
            $data['items'] = DashboardItemMiniResource::collection($data['items']);
        } else if ($data['type'] === 'course') {            
            $data['name'] = $this->name;
            $data['state'] = $this->state;
            $data['standAlone'] = $this->stand_alone;
            $data['programs'] = DashboardAttachmentResource::collection(
                $this->programs()->hasNoOwner()->get()
            );
            $data['courses'] = DashboardAttachmentResource::collection($this->courses);
            $data['grades'] = DashboardAttachmentResource::collection($this->grades);
            $data['description'] = $this->description;
            $data['prices'] = PaymentTypeResource::collection($this->prices);
            $data['subscriptions'] = PaymentTypeResource::collection($this->subscriptions);
            $data['ownedby'] = new UserAccountResource($this->ownedby);
            $data['addedby'] = new UserAccountResource($this->addedby);
            $data['facilitators'] = UserAccountResource::collection($this->facilitators);
            $data['professionals'] = UserAccountResource::collection($this->professionals);
            $data['learners'] = $this->learners->count();
            $data['lessons'] = $this->lessons->count();
            $data['sections'] = DashboardItemMiniResource::collection($this->courseSections);
            $data['discussions'] = $this->discussions->count();
            $data['classes'] = $this->classes;
            $data['classes'] = $data['classes']->merge($this->programs()->hasOwner()->get());
            $data['classes'] = DashboardItemMiniResource::collection($data['classes']);
        } else if ($data['type'] === 'extracurriculum') {
            $data['name'] = $this->name;
            $data['description'] = $this->description;
            $data['programs'] = DashboardAttachmentResource::collection(
                $this->programs()->hasNoOwner()->get()
            );
            $data['grades'] = DashboardAttachmentResource::collection($this->grades);
            $data['courses'] = DashboardAttachmentResource::collection($this->courses);
            $data['state'] = $this->state;
            $data['structure'] = $this->structure;
            $data['ownedby'] = new UserAccountResource($this->ownedby);
            $data['facilitators'] = UserAccountResource::collection($this->facilitators);
            $data['professionals'] = UserAccountResource::collection($this->professionals);
            $data['prices'] = PaymentTypeResource::collection($this->prices);
            $data['subscriptions'] = PaymentTypeResource::collection($this->subscriptions);
            $data['lessons'] = $this->lessons->count();
            $data['learners'] = $this->learners->count();
            $data['discussions'] = $this->discussions->count();
            $data['classes'] = $this->classes;
            $data['classes'] = $data['classes']->merge($this->programs()->hasOwner()->get());
            $data['classes'] = DashboardItemMiniResource::collection($data['classes']);
        } else if ($data['type'] === 'program') {
            $data['name'] = $this->name;
            $data['state'] = $this->state;
            $data['description'] = $this->description;
            $data['courses'] = $this->courses->count();
            $data['discussions'] = $this->discussions->count();
            $data['learners'] = $this->learners->count();
            $data['grades'] = DashboardAttachmentResource::collection($this->grades);
            $data['courses'] = DashboardAttachmentResource::collection(
                $this->courses()->hasNoOwner()->get()
            );
            $data['programs'] = DashboardAttachmentResource::collection(
                $this->programs()->hasNoOwner()->get()
            );
            $data['ownedby'] = new UserAccountResource($this->ownedby);
            $data['facilitators'] = UserAccountResource::collection($this->facilitators);
            $data['professionals'] = UserAccountResource::collection($this->professionals);
            $data['prices'] = PaymentTypeResource::collection($this->prices);
            $data['subscriptions'] = PaymentTypeResource::collection($this->subscriptions);
            $courses = $this->courses()->withCount('lessons')->hasOwner()->get();
            $data['items'] = $this->extracurriculums;
            $data['items'] = $data['items']->merge($courses);
            $data['items'] = DashboardItemMiniResource::collection($data['items']);
        }

        $data['createdAt'] = $this->created_at;
        $data['updatedAt'] = $this->updated_at;
        return $data;
    }
}
