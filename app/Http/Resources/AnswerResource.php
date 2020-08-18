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

        if ($this->images()->exists()) {
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
            'answerable_type' => $this->answerable_type,
            'answerable_id' => $this->answerable_id,
            'work' => $this->work,
            'possible_answer' => $this->possible_answer_id,
            'answer' => $this->answer,
            'likes' => LikeResource::collection($this->likes),
            'flags' => FlagResource::collection($this->flags),
            'score_over' => $this->answerable->score_over,
            'avg_score' => $this->marks()->avg('score'),
            'max_score' => $this->marks()->max('score'),
            'min_score' => $this->marks()->min('score'),
            'marks' => MarkResource::collection($this->marks),
            'comments_number' => $this->comments()->count(),
            'answeredby_id' => $this->answeredby_id,
            'answeredby_type' => $this->answeredby_type,
            'answeredby_name' => $this->answeredby->name,
            'url' => $this->answeredby->profile->url,
            'images' => $images,
            'videos' => $videos,
            'audios' => $audios,
            'files' => $files,
            'created_at' => $this->created_at,
        ];

        return $data;
    }
}
