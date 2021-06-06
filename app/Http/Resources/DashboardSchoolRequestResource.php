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
        $data['id'] = $this->id;
        $data['state'] = strtolower($this->state);

        if (Str::contains(Str::lower($this->requestfrom_type), 'school')) {
            $data['isFrom'] = true;

            $data['account'] = new UserAccountResource($this->requestto);
            $data['myAccount'] = new UserAccountResource($this->requestfrom);
        } 
        
        if (Str::contains(Str::lower($this->requestto_type), 'school')) {
            $data['isTo'] = true;  

            $data['account'] = new UserAccountResource($this->requestfrom);
            $data['myAccount'] = new UserAccountResource($this->requestto);
        }

        $data['images'] = ImageResource::collection($this->images);
        $data['videos'] = VideoResource::collection($this->videos);
        $data['audios'] = AudioResource::collection($this->audios);
        $data['files'] = FileResource::collection($this->files);
        $data['salaries'] = PaymentTypeResource::collection($this->salaries);
        $data['commissions'] = PaymentTypeResource::collection($this->commissions);
        $data['fees'] = PaymentTypeResource::collection($this->fees);
        $data['discounts'] = PaymentTypeResource::collection($this->discounts);
        $data['createdAt'] = $this->created_at->diffForHumans();

        $requestDTO = unserialize($this->data);
        $data['action'] = $requestDTO->action;
        $data['message'] = $requestDTO->message;

        return $data;
    }
}
