<?php

namespace App\Http\Resources\Document;
use Illuminate\Http\Resources\Json\JsonResource;

class ListOtherDocsResource extends JsonResource
{

    public function toArray($request)
    {   
       
        // dd($this->files);
        return [
            'media_id' => $this->id
        ];



    }
}
