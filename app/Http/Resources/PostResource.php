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
        } else if ($this->lessons()->exists()) {
            $typeName = 'lesson';
            $type = LessonResource::collection($this->lessons()->latest()->get());
        }

        $images = null;
        $videos = null;
        $audios = null;
        $files = null;

        if ($this->images->count()) {
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
            'content' => $this->content,
            'type' => $type,
            'typeName' => $typeName,
            'isPost' => true,
            'likes' => LikeResource::collection($this->likes),
            'comments_number' => $this->comments()->count(),
            'comments' => CommentResource::collection($this->comments()
                ->orderby('updated_at','desc')->take(1)->get()),
            'addedby' => new UserAccountResource($this->addedby),
            'flags' => FlagResource::collection($this->flags),
            'saves' => SaveResource::collection($this->beenSaved),
            'attachments' => PostAttachmentResource::collection($this->attachments),
            'images' => $images,
            'videos' => $videos,
            'audios' => $audios,
            'files' => $files,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
