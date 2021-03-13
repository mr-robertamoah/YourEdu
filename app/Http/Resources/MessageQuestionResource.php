<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageQuestionResource extends JsonResource
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
        $data = [];

        if ($this->images->count()) {
            $images = ImageResource::collection($this->images);
        } else if ($this->videos()->exists()) {
            $videos = VideoResource::collection($this->videos);
        } else if ($this->audios()->exists()) {
            $audios = AudioResource::collection($this->audios);
        } else if ($this->files()->exists()) {
            $files = FileResource::collection($this->files);
        }

        $data['images'] = $images;
        $data['videos'] = $videos;
        $data['audios'] = $audios;
        $data['files'] = $files;
        $data['id'] = $this->id;
        $data['updated_at'] = $this->updated_at;
        $data['created_at'] = $this->created_at;
        $data['userDeletes'] = $this->user_deletes;
        $data['state'] = $this->state;

        if (is_null($this->conversation_id)) {
            $data['question'] = $this->question;
            $data['questionableId'] = $this->questionable_id;
            $data['state'] = $this->state;
            $data['score_over'] = $this->score_over;
            $data['possibleAnswers'] = PossibleAnswerResource::collection($this->possibleAnswers);
            $data['answers'] = AnswerResource::collection($this->answers()->latest()->get());
            $data['user_id'] = $this->addedby->user_id;
            $data['name'] = $this->addedby->name;
        } else {
            $data['conversationId'] = $this->conversation_id;
            $data['message'] = $this->message;
            $data['toable_id'] = $this->toable_id;
            $data['toable_type'] = $this->toable_type;
            $data['to_user_id'] = $this->to_user_id;
            $data['fromable_id'] = $this->fromable_id;
            $data['fromable_type'] = $this->fromable_type;
            $data['from_user_id'] = $this->from_user_id;
        }

        return $data;
    }
}
