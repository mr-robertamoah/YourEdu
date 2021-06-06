<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
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
        } else if ($this->videos()->exists()) {
            $videos = VideoResource::collection($this->videos);
        } else if ($this->audios()->exists()) {
            $audios = AudioResource::collection($this->audios);
        } else if ($this->videos()->exists()) {
            $files = FileResource::collection($this->files);
        }

        $data = [
            'id' => $this->id,
            'answerable_type' => class_basename_lower($this->answerable_type),
            'answerable_id' => $this->answerable_id,
            'work' => $this->work,
            'possibleAnswerIds' => $this->possible_answer_ids,
            'answer' => $this->answer,
            'likes' => LikeResource::collection($this->likes),
            'flags' => FlagResource::collection($this->flags),
            'scoreOver' => $this->answerable->score_over,
            'avgScore' => $this->marks()->avg('score'),
            'maxScore' => $this->marks()->max('score'),
            'minScore' => $this->marks()->min('score'),
            'marks' => MarkResource::collection($this->marks),
            'saves' => SaveResource::collection($this->beenSaved),
            'commentsNumber' => $this->comments()->count(),
            'answeredby' => new UserAccountResource($this->answeredby),
            'images' => $images,
            'videos' => $videos,
            'audios' => $audios,
            'files' => $files,
            'created_at' => $this->created_at->diffForHumans(),
        ];

        return $data;
    }
}
