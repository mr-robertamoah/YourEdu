<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'amount' => $this->amount,
            'for' => $this->for,
            'id' => $this->id,
            'description' => $this->description,
        ];

        $data['type'] = class_basename_lower($this->resource);
        if ($data['type'] === 'subscription') {
            $data['period'] = $this->period;
        }
        return $data;
    }
}
