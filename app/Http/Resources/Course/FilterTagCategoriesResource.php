<?php

namespace App\Http\Resources\Course;

use App\Http\Requests\CatalogFilterRequest;
use App\Http\Resources\CategoryCourseFilterResource;
use App\Models\Course\CategoryCourse;
use Illuminate\Http\Resources\Json\JsonResource;

class FilterTagCategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  CatalogFilterRequest  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
        ];
        $request->route()->getActionMethod() !== 'filters' ?: 
            $result = array_merge($result, ['categories' => CategoryCourseFilterResource::collection($this->categories)->toArray($request)]);

        return $result;
    }
}
