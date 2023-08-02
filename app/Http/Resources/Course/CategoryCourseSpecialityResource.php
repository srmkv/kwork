<?php

namespace App\Http\Resources\Course;

use App\Services\CategoryCourseService;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryCourseSpecialityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * 
     * Показывает информацию для категории курса нв экране создания курса
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'category_course_id' => $this->category_course_id,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'level_education_id' => $this->level_education_id,
            'level_education' => $this->levelEducation?->title,
            'faqs' => $this->faqs()->with('questions.answer')->get(),
        ];
        
        
        

    }
}
