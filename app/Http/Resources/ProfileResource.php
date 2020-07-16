<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = [];
        $array = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_full_name' => $this->profileable->user->full_name,
            'owner' => $this->profileable,
            'owner_name' => $this->profileable->name,
            'username' => $this->profileable->user->username,
            'follows' => $this->profileable->follows()->count(),
            'followings' => $this->profileable->followings()->count(),
            'socials' => $this->socials,
            'videos' => $this->when(
                $this->videos()->exists() && 
                $this->videos->state === 'PUBLIC',
                $this->videos
            ),
            'gender' => $this->when(
                $this->profileable->user->gender,$this->profileable->user->gender
            ),
            'name' => $this->name,
            'about' => $this->about,
            'interests' => $this->interests,
            'occupation' => $this->occupation,
            'company' => $this->company,
            'location' => $this->location,
            'created_at' => $this->created_at,
            'url' => $this->url,
            // 'address' => $this->address,
        ];

        if ($this->profileable_type === "App\\YourEdu\\Learner") {
            $array['owner'] = new LearnerResource($this->profileable);
        } else if ($this->profileable_type === "App\\YourEdu\\ParentModel") {
            $array['owner'] = new ParentModelResource($this->profileable);
        } else if ($this->profileable_type === "App\\YourEdu\\Facilitator") {
            $array['owner'] = new FacilitatorResource($this->profileable);
        } else if ($this->profileable_type === "App\\YourEdu\\Professional") {
            $array['owner'] = new ProfessionalResource($this->profileable);
        } else if ($this->profileable_type === "App\\YourEdu\\School") {
            $array['owner'] = new SchoolResource($this->profileable);
        }
        return $array;
    }
}
