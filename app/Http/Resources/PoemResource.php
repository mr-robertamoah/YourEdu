<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PoemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $images = null;
        $videos = null;
        $audios = null;
        $files = null;

        if ($this->images()->exists()) {
            $images = ImageResource::collection($this->images);
        } else if ($this->videos()->exists()) {
            $videos = VideoResource::collection($this->videos);
        } else if ($this->audios()->exists()) {
            $audios = AudioResource::collection($this->audios);
        } else if ($this->videos()->exists()) {
            $files = FileResource::collection($this->files);
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'about' => $this->about,
            'comments_number' => $this->comments()->count(),
            'sections' => PoemSectionResource::collection($this->poemSections),
            'published' => $this->published,
            'images' => $images,
            'videos' => $videos,
            'audios' => $audios,
            'files' => $files,
        ];
    }
}
