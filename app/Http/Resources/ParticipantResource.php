<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantResource extends JsonResource
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
        if (is_null($this->accountable)) {
            $data['account'] = getAccountString($this->profile->profileable_type);
            $data['accountId'] = $this->id;
            $data['name'] = $this->profile->name;            
            $data['state'] = 'OWNER';
            $data['url'] = $this->profile->url;
            $data['userId'] = $this->user_id ? $this->user_id : $this->owner_id;
        } else {
            $data['account'] = getAccountString($this->accountable_type);
            $data['accountId'] = $this->accountable_id;
            $data['name'] = $this->accountable->profile->name;            
            $data['state'] = $this->state;
            $data['url'] = $this->accountable->profile->url;
            $data['userId'] = $this->user_id;
            $data['participantId'] = $this->id;
        }
        return $data;
    }
}
