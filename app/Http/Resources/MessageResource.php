<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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

        if ($this->images->count()) {
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
            'conversationId' => $this->conversation_id,
            'message' => $this->message,
            'toable_id' => $this->toable_id,
            'toable_type' => $this->toable_type,
            'to_user_id' => $this->to_user_id,
            'fromable_id' => $this->fromable_id,
            'fromable_type' => $this->fromable_type,
            'from_user_id' => $this->from_user_id,
            'state' => $this->state,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'userDeletes' => $this->user_deletes,
            'images' => $images,
            'videos' => $videos,
            'audios' => $audios,
            'files' => $files,
        ];
    }
}
