<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonalDocResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'required_docs'  => $this->append('required_docs')->required_docs,
            'other_type_docs'  => $this->append('other_type_docs')->other_type_docs,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at
        ];
    }
}
