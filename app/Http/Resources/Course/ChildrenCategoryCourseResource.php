<?php

namespace App\Http\Resources\Course;

use App\Http\Resources\SubCategoryCourseResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ChildrenCategoryCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * 
     * под категории для дерева категорий на экране создания курса
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [

            'id' => $this->id,
           'title' => $this->title,
           'page_title' => $this->page_title,
           'description' => $this->description,
           'seo_keywords' => $this->seo_keywords,
           'seo_description' => $this->seo_description,
           'seo_title' => $this->seo_title,
           'slug' => $this->slug,
           'color' => $this->color,
           'status' => $this->status,
           'speciality' => $this->speciality,
           'created_at' => $this->created_at->format('Y-m-d H:i:s'),
           'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'children' => SubCategoryCourseResource::collection($this->children),
        ];
    }
}
