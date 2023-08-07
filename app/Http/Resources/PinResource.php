<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PinResource extends JsonResource
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

            'pin_id' => $this->id,
            'user_id' => $this->user_id,
            'pin' => $this->pin,
            'count_attempts' => $this->count_attempts,
            // 'count_timeout' => $this->count_timeout,
            // 'pin' => $this->pin,


        ];
    }
}
