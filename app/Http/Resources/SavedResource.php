<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SavedResource extends JsonResource
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
        $data['id'] = $this->id;
        $data['saves'] = SaveResource::collection($this->beenSaved);
        $data['created_at'] = $this->created_at;
        $data['updated_at'] = $this->updated_at;
        if (!is_null($this->user)) {
            $data['username'] = $this->user->username;
            $data['full_name'] = $this->user->full_name;
        }
        if (!is_null($this->postedby)) {
            $data['postedby_type'] = getAccountString($this->postedby_type);
            $data['postedby_id'] = $this->postedby_id;
            $data['name'] = $this->postedby->profile->name;
            $data['url'] = $this->postedby->profile->url;
            $data['content'] = $this->content;
        }
        if (!is_null($this->commentedby)) {
            $data['commentedby_type'] = getAccountString($this->commentedby_type);
            $data['commentedby_id'] = $this->commentedby_id;
            $data['name'] = $this->commentedby->profile->name;
            $data['url'] = $this->commentedby->profile->url;
            $data['comment'] = $this->body;
        }
        if (!is_null($this->answeredby)) {
            $data['answeredby_type'] = getAccountString($this->answeredby_type);
            $data['answeredby_id'] = $this->answeredby_id;
            $data['name'] = $this->answeredby->profile->name;
            $data['answer'] = $this->answer;
            $data['url'] = $this->answeredby->profile->url;
        }
        if (!is_null($this->questions) && count($this->questions)) {
            $data['question'] = $this->questions[0]->question;
        }
        if (!is_null($this->riddles) && count($this->riddles)) {
            $data['riddle'] = $this->riddles[0]->riddle;
            $data['author'] = $this->riddles[0]->author;
        }
        if (!is_null($this->poems) && count($this->poems)) {
            $data['title'] = $this->poems[0]->title;
            $data['about'] = $this->poems[0]->about;
            $data['author'] = $this->poems[0]->author;
            $data['poem'] = $this->poems[0]->poemSections[0];
        }
        if (!is_null($this->activities) && count($this->activities)) {
            $data['activity'] = $this->activities[0]->description;
        }
        if (!is_null($this->books) && count($this->books)) {
            $data['title'] = $this->books[0]->title;
            $data['about'] = $this->books[0]->about;
            $data['author'] = $this->books[0]->author;
        }
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

        $data['images'] = $images;
        $data['videos'] = $videos;
        $data['files'] = $files;
        $data['audios'] = $audios;

        return $data;
    }
}
