<?php

namespace App\Http\Resources\General;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaRecource extends JsonResource
{

    public function toArray($request)
    {  
        return [
            'media_in_passport' => MediaItemRecource::collection($this->resource)
        ];
    }
}
