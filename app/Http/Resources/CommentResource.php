<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
        if ($this->videos()->exists()) {
            $files = FileResource::collection($this->files);
        }

        return [
            'id' => $this->id,
            'body' => $this->body,
            'likes' => LikeResource::collection($this->likes),
            'comments' => $this->comments()->count(),
            // 'comments' => new CommentResource($this->comments()->latest()->first()),
            'flags' => FlagResource::collection($this->flags),
            'saves' => SaveResource::collection($this->beenSaved),
            'commentedby' => new UserAccountResource($this->commentedby),
            'commentable_type' => $this->commentable_type,
            'commentable_id' => $this->commentable_id,
            'createdAt' => $this->created_at->diffForHumans(),
            'images' => $images,
            'videos' => $videos,
            'audios' => $audios,
            'files' => $files,
        ];
    }
}
