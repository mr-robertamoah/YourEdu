<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RequestMessageResource extends JsonResource
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
        
        return [
            'id' => $this->id,
            'requestId' => $this->messageable_id,
            'message' => $this->message,
            'fromable_id' => $this->fromable_id,
            'fromable_type' => $this->fromable_type,
            'fromable_name' => $this->fromable->profile ? $this->fromable->profile->name : $this->fromable->full_name,
            'userId' => $this->fromable->user_id ? $this->fromable->user_id : $this->fromable->id,
            'fromable_url' => $this->fromable->profile ? $this->fromable->profile->url : '',
            'fromable_userId' => $this->from_user_id,
            'state' => $this->state,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'images' => $images,
            'videos' => $videos,
            'audios' => $audios,
            'files' => $files,
        ];
    }
}
