<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class GetStudyDocsCourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [

            'replacing_docs' => $this->append('needed_edu_docs')->needed_edu_docs,
            'other_type_docs' => $this->append('other_type_docs')->other_type_docs,
            'document_id' => $this->id, 
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at

        ];
    }
}
