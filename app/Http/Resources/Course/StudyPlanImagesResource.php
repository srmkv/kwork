<?php

namespace App\Http\Resources\Course;

use App\Models\Course\Course;
use App\Traits\Path;
use Illuminate\Http\Resources\Json\JsonResource;

class StudyPlanImagesResource extends JsonResource
{
    use Path;
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
            'title' =>$this->simpleImagePath( $this->name, Course::PATH_IMG_SIMPLE)
        ];
    }
}
