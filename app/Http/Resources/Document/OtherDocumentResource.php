<?php
namespace App\Http\Resources\Document;
use Illuminate\Http\Resources\Json\JsonResource;

class OtherDocumentResource extends JsonResource
{
    public function toArray($request)
    {   
        return [
            'media_id' => $this->resource['media']->id,
            'other_doc_id' => $this['media']->getCustomProperty('other_doc_id'),
            'doc_title_type' => $this->resource['info']->title ?? null,
            'user_id' => $this['info']->user_id ?? null
        ];
    }
}

