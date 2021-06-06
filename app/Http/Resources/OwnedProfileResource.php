<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use \Debugbar;

class OwnedProfileResource extends JsonResource
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
        $data['accountId'] = $this->profileable_id;
        $data['account'] = class_basename_lower($this->profileable_type);
        $data['name'] = $this->name;
        $data['url'] = $this->url;
        $data['profile'] = $this->profileable_type;
        
        if ($this->profileable) {            
            $data['userId'] = $this->profileable->user_id ? $this->profileable->user_id :
                $this->profileable->owner_id;
        }

        // if ($data['account'] === 'parent') {
                
        // }
        
        if ($data['account'] === 'school' && $data['userId']) {
            $data['admin'] = new AdminResource(
                $this->profileable->admins()->where('user_id',auth()->id())->first());
        }
        
        // if ($data['account'] === 'learner') {

        // }
        
        // if ($data['account'] === 'professional') {

        // } 
        
        // if ($data['account'] === 'school') {

        // }

        return $data;
    }
}
