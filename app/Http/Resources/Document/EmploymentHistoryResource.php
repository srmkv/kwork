<?php
namespace App\Http\Resources\Document;
use Illuminate\Http\Resources\Json\JsonResource;

class EmploymentHistoryResource extends JsonResource
{
    public function toArray($request)
    {   
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'media_ids' => $this->append('media_ids')->media_ids,
            'title' => $this->title
        ];
    }
}
