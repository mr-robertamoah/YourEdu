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
            'followings' => $this->profileable->followings()->whereNotNull('user_id')->count(),
            'socials' => $this->socials,
            'videos' => $this->when(
                $this->videos()->exists(),
                VideoResource::collection($this->videos()->where('state','PUBLIC')->latest()->take(4)->get())
            ),
            'images' => $this->when(
                $this->images()->exists(),
                ImageResource::collection($this->images()->where('state','PUBLIC')
                ->where('thumbnail', 0)->latest()->take(4)->get())
            ),
            'audios' => $this->when(
                $this->audios()->exists(),
                AudioResource::collection($this->audios()->where('state','PUBLIC')->latest()->take(4)->get())
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
        if ($this->profileable->follows) {
            $array['follows'] = FollowResource::collection($this->profileable->follows);
        } else {
            $array['follows'] = [];
        }
        // dd($array);

        if ($this->profileable_type === "App\\YourEdu\\Learner") {
            $array['owner'] = $this->profileable;
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
