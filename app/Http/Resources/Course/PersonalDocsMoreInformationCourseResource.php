<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Order\MoreInformationDocumentService;

class PersonalDocsMoreInformationCourseResource extends JsonResource
{

    public function toArray($request)
    {   
        // dd()
        return [

            'id' => $this->id,
            // 'required_docs'  => $this->append('required_docs')->required_docs,
            'required_docs'  => MoreInformationDocumentService::personalDocument($this->append('required_docs')->required_docs),
            'other_type_docs'  => MoreInformationDocumentService::otherTypeDocs($this->append('other_type_docs')->other_type_docs),
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at

        ];
    }
}
