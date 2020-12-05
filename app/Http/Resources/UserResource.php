<?php

namespace App\Http\Resources;

use App\YourEdu\Admin;
use App\YourEdu\Profile;
use Illuminate\Http\Resources\Json\JsonResource;
use \Debugbar;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $admin = Admin::where('user_id',$this->id)->where(function($query){
                $query->where('role','SUPERADMIN')
                    ->orWhere('role','SUPERVISOR');
            })->first();

        $profiles = $this->profiles;
        $profiles = $profiles->merge(Profile::whereHasMorph('profileable','App\YourEdu\School',function($query){
                $query->hasMyAdmin($this->id);
            })->get());

        return [
            'full_name' => $this->full_name,
            'username' => $this->username,
            'secret_answer' => $this->secret_answer,
            'dob' => $this->dob,
            'age' => $this->age,
            'bans' => BanResource::collection($this->hasBan()->get()),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'other_names' => $this->other_names,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_superadmin' => $this->is_superadmin,
            'is_supervisoradmin' => $this->is_supervisoradmin,
            'id' => $this->id,
            'gender' => $this->gender,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'profiles' => OwnedProfileResource::collection($profiles),
            'admin' => $admin ? new AdminResource($admin) : null
        ];
    }
}
