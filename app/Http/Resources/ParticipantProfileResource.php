<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantProfileResource extends JsonResource
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
        
        $data['account'] = $this->profileable->accountType;
        $data['accountId'] = $this->profileable->id;
        $data['name'] = $this->name;
        $data['url'] = $this->url;
        $data['userId'] = $this->profileable->user_id;

        if (count($this->resource->profileable['participants'])) {
            $data['state'] = $this->resource->profileable['participants'][0]->state ? strtolower($this->resource->profileable['participants'][0]->state) : null;
            $data['participantId'] = $this->resource->profileable['participants'][0]->id;
            
            return $data;
        }
        
        $data['state'] = 'owner';

        return $data;
    }
}
