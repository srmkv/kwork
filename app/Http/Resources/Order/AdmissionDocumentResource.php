<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class AdmissionDocumentResource extends JsonResource
{

    public function toArray($request)
    {
        return [

            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'user_documents' => $this->append('user_documents')->user_documents,
            'need_documents' => $this->append('need_documents')->need_documents,
            'admin_comment' => $this->comment,
            'type' => $this->type
        ];
    }
}
