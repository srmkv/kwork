<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryCourseShortResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'parent_id' => $this->parent_id,
            'tree' => $this->tree,
            'filter_tag' => $this->filterTag
        ];
    }
}
