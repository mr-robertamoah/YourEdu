<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
        } else if ($this->files()->exists()) {
            $files = FileResource::collection($this->files);
        }

        return [
            'id' => $this->id,
            'question' => $this->question,
            'questionId' => $this->questionable_id,
            'state' => $this->state,
            'score_over' => $this->score_over,
            'sections' => $this->poemSections,
            'published' => $this->published,
            'updated_at' => $this->updated_at,
            'state' => $this->state,
            'possible_answers' => PossibleAnswerResource::collection($this->possibleAnswers),
            'answers_number' => $this->answers()->count(),
            'answers' => $this->answers()->latest(),
            'images' => $images,
            'videos' => $videos,
            'audios' => $audios,
            'files' => $files,
        ];
    }
}
