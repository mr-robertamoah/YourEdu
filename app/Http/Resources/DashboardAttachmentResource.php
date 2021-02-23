<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use \Debugbar;

class DashboardAttachmentResource extends JsonResource
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
        $data['type'] = class_basename_lower($this->resource);
        // Debugbar::info($this->resource);
        if ($data['type'] === 'subject') {
            $data['data']['id'] = $this->id;
            $data['data']['name'] = $this->name;
            $data['data']['description'] = $this->description;
            $data['data']['rationale'] = $this->rationale;
            $data['data']['aliases'] = SubjectAliasResource::collection($this->aliases);
        } else if ($data['type'] === 'grade') {
            $data['data']['id'] = $this->id;
            $data['data']['name'] = $this->name;
            $data['data']['description'] = $this->description;
            $data['data']['age_group'] = $this->age_group;
            $data['data']['aliases'] = SubjectAliasResource::collection($this->aliases);
        } else if ($data['type'] === 'course') {
            $data['data']['id'] = $this->id;
            $data['data']['name'] = $this->name;
            $data['data']['description'] = $this->description;
            $data['data']['aliases'] = SubjectAliasResource::collection($this->aliases);
        } else if ($data['type'] === 'program') {
            $data['data']['id'] = $this->id;
            $data['data']['name'] = $this->name;
            $data['data']['description'] = $this->description;
            $data['data']['aliases'] = SubjectAliasResource::collection($this->aliases);
        } else if ($data['type'] === 'class' ||
            $data['type'] === 'program') {
            $data['data']['id'] = $this->id;
            $data['data']['name'] = $this->name;
            $data['data']['description'] = $this->description;
            $data['data']['learners'] = $this->learners->count();
            $data['data']['lessons'] = $this->lessons->count();
            $data['data']['createdAt'] = $this->created_at;
            $data['data']['updatedAt'] = $this->updated_at;
        }

        $data['type'] = $data['type'] . 's';
        return $data;
    }
}
