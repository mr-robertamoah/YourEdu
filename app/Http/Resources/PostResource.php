<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $type = null;
        $typeName = null;
        if ($this->books()->exists()) {
            $typeName = 'book';
            $type = BookResource::collection($this->books()->latest()->get());
        } else if ($this->poems()->exists()) {
            $typeName = 'poem';
            $type = PoemResource::collection($this->poems()->latest()->get());
        } else if ($this->riddles()->exists()) {
            $typeName = 'riddle';
            $type = RiddleResource::collection($this->riddles()->latest()->get());
        } else if ($this->activities()->exists()) {
            $typeName = 'activity';
            $type = ActivityResource::collection($this->activities()->latest()->get());
        } else if ($this->questions()->exists()) {
            $typeName = 'question';
            $type = QuestionResource::collection($this->questions()->latest()->get());
        }

        $images = null;
        $videos = null;
        $audios = null;
        $files = null;

        if ($this->images()->exists()) {
            $images = new ImageCollection($this->images);
        } else if ($this->videos()->exists()) {
            $videos = new VideoCollection($this->videos);
        } else if ($this->audios()->exists()) {
            $audios = new AudioCollection($this->audios);
        } else if ($this->videos()->exists()) {
            $files = new FileCollection($this->files);
        }

        return [
            'id' => $this->id,
            'content' => $this->content,
            'type' => $type,
            'typeName' => $typeName,
            'likes' => LikeResource::collection($this->likes),
            'comments_number' => $this->comments()->count(),
            'comments' => CommentResource::collection($this->comments()->latest()->take(2)->get()),
            'postedby' => $this->postedby->name,
            'postedby_type' => $this->postedby_type,
            'postedby_id' => $this->postedby_id,
            'postedby_id' => $this->postedby_id,
            'profile_url' => $this->postedby->profile->url,
            'flags' => $this->flags,
            'images' => $images,
            'videos' => $videos,
            'audios' => $audios,
            'files' => $files,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
