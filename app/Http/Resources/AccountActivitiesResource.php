<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountActivitiesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [];
        $data['activity'] = getAccountString($this->resource);
        $data['activityId'] = $this->id;

        if ($data['activity'] === 'post') {
            $data['content'] = $this->content;
            $data['images'] = ImageResource::collection($this->images);
            $data['videos'] = VideoResource::collection($this->videos);
            $data['audios'] = AudioResource::collection($this->audios);
        } else if ($data['activity'] === 'comment') {
            $data['item'] = getAccountString($this->commentable_type);
            $data['itemId'] = $this->commentable_id;
            $data['comment'] = $this->body;            
        } else if ($data['activity'] === 'discussion') {
            $data['title'] = $this->title;            
            $data['restricted'] = $this->restricted;            
            $data['type'] = $this->type;            
            $data['allowed'] = $this->allowed;            
            $data['preamble'] = $this->preamble;
            $data['images'] = ImageResource::collection($this->images);
            $data['videos'] = VideoResource::collection($this->videos);
            $data['audios'] = AudioResource::collection($this->audios);     
        } else if ($data['activity'] === 'message') {
            $data['item'] = getAccountString($this->messageable_type);
            $data['itemId'] = $this->messageable_id;
            $data['message'] = $this->message;
            $data['state'] = $this->state;
            $data['images'] = ImageResource::collection($this->images);
            $data['videos'] = VideoResource::collection($this->videos);
            $data['audios'] = AudioResource::collection($this->audios);            
        } else if ($data['activity'] === 'answer') {
            $data['item'] = getAccountString($this->answerable_type);
            $data['itemId'] = $this->answerable_id;
            $data['possible_answer'] = $this->possible_answer_id;
            $data['answer'] = $this->answer;
            $data['images'] = ImageResource::collection($this->images);
            $data['videos'] = VideoResource::collection($this->videos);
            $data['audios'] = AudioResource::collection($this->audios);
        } else if ($data['activity'] === 'question') {
            $data['item'] = getAccountString($this->questionable_type);
            $data['itemId'] = $this->questionable_id;
            $data['question'] = $this->question;
            $data['state'] = $this->state;
            $data['published'] = $this->published;
            $data['about'] = $this->score_over;  
            $data['images'] = ImageResource::collection($this->images);
            $data['videos'] = VideoResource::collection($this->videos);
            $data['audios'] = AudioResource::collection($this->audios);          
        } else if ($data['activity'] === 'riddle') {
            $data['item'] = getAccountString($this->riddleable_type);
            $data['itemId'] = $this->riddleable_id;
            $data['riddle'] = $this->riddle;
            $data['author'] = $this->author;
            $data['published'] = $this->published;
            $data['scoreOver'] = $this->score_over;  
            $data['images'] = ImageResource::collection($this->images);
            $data['videos'] = VideoResource::collection($this->videos);
            $data['audios'] = AudioResource::collection($this->audios);          
        } else if ($data['activity'] === 'poem') {
            $data['item'] = getAccountString($this->poemable_type);
            $data['itemId'] = $this->poemable_id;
            $data['author'] = $this->author;
            $data['title'] = $this->title;
            $data['published'] = $this->published;
            $data['about'] = $this->score_over;  
            $data['images'] = ImageResource::collection($this->images);
            $data['videos'] = VideoResource::collection($this->videos);
            $data['audios'] = AudioResource::collection($this->audios);          
        } else if ($data['activity'] === 'lesson') {
            $data['item'] = getAccountString($this->lessonable_type);
            $data['itemId'] = $this->lessonable_id;
            $data['description'] = $this->description;
            $data['title'] = $this->title;
            $data['images'] = ImageResource::collection($this->images);
            $data['videos'] = VideoResource::collection($this->videos);
            $data['audios'] = AudioResource::collection($this->audios);          
        } else if ($data['activity'] === 'activity') {
            $data['item'] = getAccountString($this->activityfor_type);
            $data['itemId'] = $this->activityfor_id;
            $data['description'] = $this->description;
            $data['published'] = $this->published;
            $data['images'] = ImageResource::collection($this->images);
            $data['videos'] = VideoResource::collection($this->videos);
            $data['audios'] = AudioResource::collection($this->audios);          
        } else if ($data['activity'] === 'like') {
            $data['item'] = getAccountString($this->likeable_type);
            $data['itemId'] = $this->likeable_id;            
        } else if ($data['activity'] === 'flag') {
            $data['reason'] = $this->reason;
            $data['item'] = getAccountString($this->flaggable_type);
            $data['itemId'] = $this->flaggable_id;
            $data['status'] = $this->status;
        }
        $data['createdAt'] = $this->created_at;
        $data['updatedAt'] = $this->updated_at;
        
        return $data;
        // return parent::toArray($request);
    }
}
