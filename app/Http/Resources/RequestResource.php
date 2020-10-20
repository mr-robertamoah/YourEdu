<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use \Debugbar;

class RequestResource extends JsonResource
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
        $data['userId'] = $this->requestfrom->user_id;
        if ($this->requestable_type === 'App\YourEdu\Follow') {
            $data['account_type'] = getAccountString($this->requestfrom_type);
            $data['account_id'] = $this->requestfrom_id;
            $data['myAccount'] = getAccountString($this->requestto_type);
            $data['myAccountId'] = $this->requestto_id;
            $data['myName'] = $this->requestto->profile->name;
            $data['name'] = $this->requestfrom->profile->name;
            $data['url'] = $this->requestfrom->profile->url;
            $data['isAccount'] = true;
        } else if ($this->requestable_type === 'App\YourEdu\Message') {
            
            $images = null;
            $videos = null;
            $audios = null;
            $files = null;

            if ($this->requestable->images()->exists()) {
                $images = ImageResource::collection($this->requestable->images);
            } 
            if ($this->requestable->videos()->exists()) {
                $videos = VideoResource::collection($this->requestable->videos);
            } 
            if ($this->requestable->audios()->exists()) {
                $audios = AudioResource::collection($this->requestable->audios);
            } 
            if ($this->requestable->files()->exists()) {
                $files = FileResource::collection($this->requestable->files);
            }
            
            $data['isMessage'] = true;
            $data['images'] = $images;
            $data['videos'] = $videos;
            $data['audios'] = $audios;
            $data['files'] = $files;
            $data['message'] = $this->requestable->message;
            $data['messageId'] = $this->requestable->id;
            $data['fromable_type'] = $this->requestfrom_type;
            $data['fromable_id'] = $this->requestfrom_id;
            $data['fromable_name'] = $this->requestfrom->profile->name;
            $data['fromable_url'] = $this->requestfrom->profile->url;
        } else if ($this->requestable_type === 'App\YourEdu\Discussion') {
            $array = $this->requestable->participants->pluck('user_id');
            $array[] = $this->requestable->raisedby->user_id;
            
            $data['isAdmin'] = array_search($data['userId'],$array->toArray());
            $data['isParticipant'] = true;
            $data['title'] = $this->requestable->title;
            $data['created_at'] = $this->requestable->created_at;
            $data['name'] = $this->requestfrom->profile->name;
            $data['url'] = $this->requestfrom->profile->url;
            $data['account'] = getAccountString($this->requestfrom_type);
            $data['accountId'] = $this->requestfrom_id;
            $data['discussionId'] = $this->requestable->id;
        }
        return $data;
    }
}
