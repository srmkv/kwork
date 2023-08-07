<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Course\MoreInformationDocEduResource;
use App\Services\Order\MoreInformationDocumentService;

class GetStudyMoreInformationDocsCourse extends JsonResource
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
            'document_id' => $this->id, 
            'title' => $this->title,
            'array_documents' => MoreInformationDocEduResource::collection($this->append('needed_edu_docs')->needed_edu_docs),
            'other_type_docs' => MoreInformationDocumentService::otherTypeDocs($this->append('other_type_docs')->other_type_docs),
            'description' => $this->description,
            'created_at' => $this->created_at

        ];
    }
}
