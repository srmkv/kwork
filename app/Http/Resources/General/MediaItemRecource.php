<?php

namespace App\Http\Resources\General;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaItemRecource extends JsonResource
{   
    public function toArray($request)
    {   
        return [
            'media_id' => $this->resource['id'],
            'model_type' => $this->resource['model_type'],
            'passport_id' => $this->resource['model_id'],
        ];
    }
}
