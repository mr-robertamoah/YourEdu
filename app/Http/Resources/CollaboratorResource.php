<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CollaboratorResource extends JsonResource
{
    private static $collaboration;

    public static function newCollection($resource, $collaboration) {
        static::$collaboration = $collaboration;
        return parent::collection($resource);
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->profile ? $this->profile->name : $this->name,
            'accountId' => $this->id,
            'account' => strtolower(class_basename($this->resource)),
            'userId' => $this->user_id,
            'state' => $this->pivot->state,
            'createdAt' => $this->pivot->created_at,
            'url' => $this->profile ? $this->profile->url : '',
            'share'=> $this->getCommissionShare(static::$collaboration),
        ];
    }
}
