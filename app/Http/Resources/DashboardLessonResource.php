<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardLessonResource extends JsonResource
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
            'title' => $this->title,
            'state' => $this->state,
            'ownedby' => new UserAccountResource($this->ownedby),
            'addedby' => new UserAccountResource($this->addedby),
            'description' => $this->description,
            'links' => DashboardItemMiniResource::collection($this->links),
            'prices' => PaymentTypeResource::collection($this->prices),
            'discussions' => DiscussionResource::collection($this->discussions),
            'images' => ImageResource::collection($this->images),
            'videos' => VideoResource::collection($this->videos),
            'audios' => AudioResource::collection($this->audios),
            'files' => FileResource::collection($this->files),
        ];
        $data['items'] = $this->classSubjects;
        $data['items'] = $data['items']->merge($this->courseSections);
        $data['items'] = $data['items']->merge($this->courses()->hasOwner()->get());
        $data['items'] = DashboardItemMiniResource::collection($data['items']);
        $data['attachments'] = $this->programs()->hasNoOwner()->get();
        $data['attachments'] = $data['attachments']->merge($this->grades);
        $data['attachments'] = $data['attachments']->merge($this->subjects);
        $data['attachments'] = $data['attachments']->merge($this->courses()->hasNoOwner()->get());
        $data['attachments'] = DashboardItemMiniResource::collection($data['attachments']);
        $data['createdAt'] = $this->created_at;
        $data['updatedAt'] = $this->updated_at;

        return $data;
    }
}
