<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SearchResource extends JsonResource
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
        $data['created_at'] = $this->created_at;
        $data['updated_at'] = $this->updated_at;
        $data['url'] = $this->when($this->profileable,$this->url);
        if (!is_null($this->user)) {
            $data['username'] = $this->user->username;
            $data['full_name'] = $this->user->full_name;
        }
        if ($this->profileable) {
            $data['account_type'] = getAccountString($this->profileable_type);
            if ($data['account_type'] === 'school') {
                $data['name'] = $this->profileable->company_name;
                
            } else if ($data['account_type'] !== 'school') {
                $data['name'] = $this->name;
            }
            $data['about'] = $this->about;
            $data['user_id'] = $this->user_id;
            $data['account_id'] = $this->profileable_id;
            $data['follows'] = FollowResource::collection(
                $this->profileable->follows()->whereNotNull('user_id')->get());
        }
        if (!is_null($this->postedby)) {
            $data['postedby_type'] = getAccountString($this->postedby_type);
            $data['postedby_id'] = $this->postedby_id;
            $data['name'] = $this->postedby->profile->name;
            $data['url'] = $this->postedby->profile->url;
        }
        $data['content'] = $this->when($this->content,$this->content);
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

        return $data;
    }
}
