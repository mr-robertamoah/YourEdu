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
        $data['account_id'] = $this->profileable_id;
        $data['account_type'] = class_basename_lower($this->profileable_type);
        $data['profile_name'] = $this->name;
        $data['profile_url'] = $this->url;
        $data['profile'] = $this->profileable_type;
        if ($this->profileable) {            
            $data['userId'] = $this->profileable->user_id ? $this->profileable->user_id :
                $this->profileable->owner_id;
        }
        if ($data['account_type'] === 'parent') {
                
        } else if ($data['account_type'] === 'school' && $data['userId']) {
            $data['admin'] = new AdminResource(
                $this->profileable->admins()->where('user_id',auth()->id())->first());
        } else if ($data['account_type'] === 'learner') {

        } else if ($data['account_type'] === 'professional') {

        }  else if ($data['account_type'] === 'school') {

        }
        return $data;
    }
}
