<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class PriceResource extends JsonResource
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

            'old_price' => $this->old_price,
            'new_price' => $this->new_price,
            'instalment_price' => $this->instalment_price,
            'default_price' => $this->default_price,
            'discount_percent' => $this->discount

        ];
    }
}
