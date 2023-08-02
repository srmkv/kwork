<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class BusinessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [

            'full_title' => $this->full_title,
            'short_title' => $this->short_title,
            'business_address' => $this->buiseness_address,
            'inn' => $this->inn,
            'phone' => $this->phone_company,
            'fact_address' => $this->fact_address,
            'management_position' => $this->management_position
        ];
    }
}
