<?php

namespace App\Http\Resources\Course;

use App\Models\Course\Course;
use App\Traits\Path;
use Dotenv\Store\File\Paths;
use Illuminate\Http\Resources\Json\JsonResource;

class WhoSuitedResouce extends JsonResource
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
            'description' => $this->description,
            'icon_color' =>$this->icon_color,
            'icon' => $this->simpleImagePath($this->icon, Course::PATH_ICONS_WHOS)
        ];
    }
}
