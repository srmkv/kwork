<?php

namespace App\Http\Resources;

use App\Models\Course\BannerContentType;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'id' => $this->id,
            'type_id' => $this->type_id,
            'color' => $this->color,
            'image' => $this->media()->where('content_type_id', BannerContentType::PHOTO_CONTENT_TYPE)->first(),
        ];
    }
}
