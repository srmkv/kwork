<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class SpecialityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [

            'title' => $this->title,
            // 'categoriesCourse' => $this->categoriesCourse()

            'description' => $this->description,
            // 'courses' => CourseResource::collection($this->whenLoaded('courses'))
            
            // уровень образования
            'level_education' => $this->levelEducation,
            

            // надо ли этой тянуть?..
            // 'courses' => CourseResource::collection($this->courses)


            // 'course'


        ];
    }
}
