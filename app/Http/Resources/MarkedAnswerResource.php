<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MarkedAnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $isAnsweredby = $this->answeredby->user_id == $request->user()?->id;

        return [
            'id' => $this->id,
            'question' => new QuestionResource($this->answerable),
            'possibleAnswerIds' => $this->possible_answer_ids,
            'assessmentSectionId' => $this->answerable->questionable->id,
            'answer' => $this->answer,
            'scoreOver' => $this->answerable->score_over,
            'mark' => $this->when(
                !$isAnsweredby,
                new MarkResource($this->getMarkMarkedbyUser($request->user()?->id))
            ),
            'marks' => $this->when(
                $request->marks && json_decode($request->marks),
                MarkExtraResource::collection($this->marks)
            ),
            'isMarker' => !$isAnsweredby,
            'avgScore' => $this->when($isAnsweredby, $this->marks()->avg('score')),
            'maxScore' => $this->when($isAnsweredby, $this->marks()->max('score')),
            'minScore' => $this->when($isAnsweredby, $this->marks()->min('score')),
            'images' => ImageResource::collection($this->images),
            'videos' => VideoResource::collection($this->videos),
            'audios' => AudioResource::collection($this->audios),
            'files' => FileResource::collection($this->files),
            'createdAt' => $this->created_at->diffForHumans(),

        ];
    }
}
