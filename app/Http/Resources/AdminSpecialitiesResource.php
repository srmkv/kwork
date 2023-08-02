<?php

namespace App\Http\Resources;

use App\Models\Course\CategoryCourse;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminSpecialitiesResource extends JsonResource
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
            'id' => $this->id,
            'title' =>$this->title,
            'slug' =>$this->slug,
            'level_education' => $this->level_education,
            'napravlenie' => $this->napravlenie
        ];
    }
}
