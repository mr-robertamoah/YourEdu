<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
            'questions' => ChatQuestionResource::collection($this->questions),
            'toAccount' => new UserAccountResource($this->toable),
            'fromAccount' => new UserAccountResource($this->fromable),
            'state' => $this->state,
            'created_at' => Carbon::parse($this->created_at)->diffForHumans(),
            'userDeletes' => $this->user_deletes,
            'userSeens' => $this->user_seens,
            'images' => $images,
            'videos' => $videos,
            'audios' => $audios,
            'files' => $files,
        ];
    }
}
