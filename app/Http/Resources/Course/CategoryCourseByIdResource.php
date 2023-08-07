<?php

namespace App\Http\Resources\Course;

use App\Models\Course\CategoryCourse;
use App\Services\CategoryCourseService;
use App\Traits\Path;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryCourseByIdResource extends JsonResource
{
    use Path;
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
        $tree = $this->tree ? [json_decode($this->tree)] : [];
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->simpleImagePath($this->image, CategoryCourse::PATH_IMG),
            'subtitle' => isset($this->filterTag) ? '(' . $this->filterTag->title . ') ID ' . $this->id : '',
            'parent_ids' => $tree,
            'count_courses' => $this->count_courses,
            'children' => CategoryCourseByIdResource::collection($this->children),
            'page_title' => $this->page_title,
            'description' => $this->description,
            'seo_keywords' => $this->seo_keywords,
            'seo_description' => $this->seo_description,
            'seo_title' => $this->seo_title,
            'slug' => $this->slug,
            'color' => $this->color,
            'status' => (int)$this->status,
            'tag_id' => $this->tag_id,
            'speciality' => $this->speciality ? CategoryCourseSpecialityResource::make($this->speciality) : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null ,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];

    }
}
