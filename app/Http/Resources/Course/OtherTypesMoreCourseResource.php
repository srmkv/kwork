<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Services\Order\MoreInformationDocumentService;

class OtherTypesMoreCourseResource extends JsonResource
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

            'id' => $this->id,
            'other_types_array'  =>  MoreInformationDocumentService::otherTypeDocs($this->append('required_types')->required_types),
            'description' => $this->description,
            'title' => $this->title,
            'created_at' => $this->created_at

        ];
    }
}
