<?php

namespace App\Http\Resources\Course;

use App\Models\Course\Course;
use App\Traits\Path;
use Illuminate\Http\Resources\Json\JsonResource;

class EduOrganizationResource extends JsonResource
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
            'name' => $this->name,
            'image' => $this->simpleImagePath($this->image, Course::PATH_IMG_SIMPLE),
        ];
    }
}
