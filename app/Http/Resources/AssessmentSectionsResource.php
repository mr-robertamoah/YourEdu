<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class AssessmentSectionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    { 
        return [
            'id' => $this->id,
            'name' => $this->name,
            'instruction' => $this->instruction,
            'random' => $this->random,
            'answerType' => $this->answer_type,
            'autoMark' => $this->auto_mark,
            'questions' => $this->when(
                $this->max_questions,
                QuestionResource::collection(
                    $this->random ? 
                    $this->questions->shuffle()->take($this->max_questions) :
                    $this->questions->take($this->max_questions)
                ),
                QuestionResource::collection($this->questions)
            ),
        ];
    }
}
