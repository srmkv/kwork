<?php

namespace App\Http\Resources\Course;

use App\Services\CategoryCourseService;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * 
     * Главное меню
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $pivotTree = $this->pivot ? \Str::replace(']', ', '.(string)$this->id . ']', $this->pivot->tree) : null;
        
        $children = $this->children
        ->where('status',1)
        ->where('id', '!=', (int)$request->id);
        
        if($pivotTree){
            $children = $children->where('pivot.tree', $pivotTree);
        }

        $result = [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'tag_id' => $this->tag_id,
            'url' => isset($this->filterTag) ? $this->filterTag->slug . '=' . $this->slug : '',
            'children' => MenuResource::collection($children),
        ];

        return $result;

    }
}
