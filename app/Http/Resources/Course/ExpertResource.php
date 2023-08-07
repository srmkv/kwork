<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpertResource extends JsonResource
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

            'fio' => $this->full_name,
            'photo' => $this->photo,
            'desccriptions' => $this->description,
            'graduate_degree' => $this->graduate_degree,
            'position' => $this->position
        ];
    }
}
