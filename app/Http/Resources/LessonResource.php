<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'ageGroup' => $this->age_group,
            'publishedAt' => $this->published_at,
            'images' => $this->images->count() ? ImageResource::collection($this->images) : null,
            'videos' => $this->videos()->exists() ? VideoResource::collection($this->images) : null,
            'audios' => $this->audios()->exists() ? AudioResource::collection($this->images) : null,
            'files' => $this->files()->exists() ? FileResource::collection($this->images) : null,
        ];
    }
}
