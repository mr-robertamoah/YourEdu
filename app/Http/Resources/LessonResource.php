<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use \Debugbar;

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
        $images = null;
        $videos = null;
        $audios = null;
        $files = null;

        if ($this->images()->exists()) {
            $images = ImageResource::collection($this->images);
        }
        if ($this->videos()->exists()) {
            $videos = VideoResource::collection($this->videos);
        }
        if ($this->audios()->exists()) {
            $audios = AudioResource::collection($this->audios);
        }
        if ($this->files()->exists()) {
            $files = FileResource::collection($this->files);
        }

        Debugbar::info($this->audios);
        Debugbar::info($audios);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'ageGroup' => $this->ageGroup,
            'images' => $images,
            'videos' => $videos,
            'audios' => $audios,
            'files' => $files,
        ];
    }
}
