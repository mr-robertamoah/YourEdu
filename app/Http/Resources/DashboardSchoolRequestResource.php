<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class DashboardSchoolRequestResource extends JsonResource
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
        $data['state'] = $this->state;
        $data['id'] = $this->id;
        if (Str::contains(Str::lower($this->requestfrom_type), 'school')) {
            $data['schoolId'] = $this->requestfrom_id;
            if ($this->requestto->username) {
                $data['username'] = $this->requestto->username;
            } else {
                $data['account'] = new UserAccountResource($this->requestto);
                $data['username'] = $this->requestto->user->username;
            }
            $data['isFrom'] = true;
        } else if (Str::contains(Str::lower($this->requestto_type), 'school')) {
            $data['isTo'] = true;  
            $data['schoolId'] = $this->requestto_id;
            if ($this->requestfrom->username) {
                $data['username'] = $this->requestfrom->username;
            } else {
                $data['account'] = new UserAccountResource($this->requestfrom);
                $data['username'] = $this->requestfrom->user->username;
            }        
        }

        if (!is_null($this->data)) {
            $unserializedData = unserialize($this->data);
            if (Arr::has($unserializedData,'adminDetails')) {
                $data['data'] = $unserializedData['adminDetails'];
            }
            if (Arr::has($unserializedData,'file')) {             
                foreach ($unserializedData['file'] as $file) {
                    $files[] = getYourEduModel($file['type'],$file['id']);
                }
                $data['file'] = ImageResource::collection($files);
            }
            if (Arr::has($unserializedData,'salary')) {
                $data['salary'] = $unserializedData['salary'];
            }
        }

        return $data;
    }
}
