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

        if ($this->images->count()) {
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
            'body' => $this->body,
            'questionId' => $this->questionable_id,
            'state' => $this->state,
            'scoreOver' => $this->score_over,
            'position' => $this->position,
            'hint' => $this->hint,
            'answerType' => $this->answer_type,
            'publishedAt' => $this->published_at,
            'updated_at' => $this->updated_at,
            'state' => $this->state,
            'correctPossibleAnswers' => $this->correct_possible_answers,
            'possibleAnswers' => PossibleAnswerResource::collection(
                $this->possibleAnswers()->orderedByPosition()->get()
            ),
            'answers_number' => $this->answers()->count(),
            'answers' => $this->answers()->latest(),
            'images' => $images,
            'videos' => $videos,
            'audios' => $audios,
            'files' => $files,
        ];
    }
}
