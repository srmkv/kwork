<?php

namespace App\Http\Resources\Media;

use Illuminate\Http\Resources\Json\JsonResource;

class ListMediaResource extends JsonResource
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

            'media_id' => $this->id,
            'collection' => $this->collection_name,
            'page'  => $this->custom_properties['page'] ?? null

        ];
    }
}



 // "id": 182,
 //        "model_type": "App\\Models\\SecondaryEdu",
 //        "model_id": 1,
 //        "uuid": "e48ca18d-7cc4-4a3a-b1c1-ce3e5b244935",
 //        "collection_name": "secondary_school",
 //        "name": "2222",
 //        "file_name": "2222.JPG",
 //        "mime_type": "image/jpeg",
 //        "disk": "media",
 //        "conversions_disk": "media",
 //        "size": 18160,
 //        "manipulations": [],
 //        "custom_properties": {
 //            "page": 1,
 //            "user_id": 133,
 //            "secondary_school_id": 1
 //        },
 //        "generated_conversions": [],
 //        "responsive_images": [],
 //        "order_column": 1,
 //        "created_at": "2022-10-07T00:12:12.000000Z",
 //        "updated_at": "2022-10-07T00:12:12.000000Z",
 //        "original_url": "/storage/182/2222.JPG",
 //        "preview_url": ""