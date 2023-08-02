<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class OtherTypesCourseResource extends JsonResource
{

    public function toArray($request)
    {
        return [    
            'id' => $this->id,
            'other_types_checked'  => $this->append('required_types')->required_types,
            'description' => $this->description,
            'title' => $this->title,
            'created_at' => $this->created_at

        ];


    }
}
