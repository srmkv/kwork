<?php

namespace App\Http\Resources;

use App\Http\Resources\Course\CatalogCategoryCourseResource;
use App\Http\Resources\Course\CategoryCourseResource;
use App\Models\Course\CategoryCourse;
use Illuminate\Http\Resources\Json\JsonResource;

class FilterCategoryTagResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'categories' => CatalogCategoryCourseResource::collection($this->categories),
        ];
    }
}
