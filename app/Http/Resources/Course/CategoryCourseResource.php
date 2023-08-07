<?php

namespace App\Http\Resources\Course;

use App\Models\Course\CategoryCourse;
use App\Services\CategoryCourseService;
use App\Traits\Path;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryCourseResource extends JsonResource
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
        $pivotTree = $this->pivot ? \Str::replace(']', ', '.(string)$this->id . ']', $this->pivot->tree) : null;

        $children = $this->children;
        if(isset($request->published) && (int)$request->published === 1){
            $children = $children->where('status',1);
        }
        $children = $children->where('id', '!=', (int)$request->id);
        
        if($pivotTree){
            $children = $children->where('pivot.tree', $pivotTree);
        }

        $result = [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->simpleImagePath($this->image, CategoryCourse::PATH_IMG),
            'slug' => $this->slug,
            'subtitle' => isset($this->filterTag) ? '(' . $this->filterTag->title . ') ID ' . $this->id : '',
            'parent_ids' => json_decode($this->tree) ?? [],
            'count_courses' => $this->count_courses,
            'children' => CategoryCourseResource::collection($children),
            'page_title' => $this->page_title,
            'description' => $this->description,
            'seo_keywords' => $this->seo_keywords,
            'seo_description' => $this->seo_description,
            'seo_title' => $this->seo_title,
            'color' => $this->color,
            'status' => (int)$this->status,
            'tag_id' => $this->tag_id,
            'speciality' => $this->speciality ? CategoryCourseSpecialityResource::make($this->speciality) : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null ,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
        
        
        switch($request->route()->getActionMethod()){
            case 'show':
                $result = array_merge($result, ['courses' => 
                CourseResource::collection($this->courses)]);
            break;
        }

       return $result;

    }
}
