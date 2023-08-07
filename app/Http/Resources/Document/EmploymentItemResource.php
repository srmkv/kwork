<?php
namespace App\Http\Resources\Document;
use Illuminate\Http\Resources\Json\JsonResource;

class EmploymentItemResource extends JsonResource
{
    public function toArray($request)
    {   
        return [

            'change_fio_doc_id' => $this->resource['model_id'] ?? null,
            'media_id' => $this->resource['id'] ?? null, 
            'collection_name' => 'user_name_replacement',
        ];
        
    }
}
