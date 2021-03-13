<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatQuestionResource extends JsonResource
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
            'question' => $this->question,
            'questionableId' => $this->questionable_id,
            'user_id' => $this->addedby->user_id,
            'name' => $this->addedby->name,
            'state' => $this->state,
            'score_over' => $this->score_over,
            'sections' => $this->poemSections,
            'published' => $this->published,
            'state' => $this->state,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'userDeletes' => $this->user_deletes,
            'possibleAnswers' => PossibleAnswerResource::collection($this->possibleAnswers),
            'answers' => AnswerResource::collection($this->answers()->latest()->get()),
            'images' => $images,
            'videos' => $videos,
            'audios' => $audios,
            'files' => $files,
        ];
    }
}
