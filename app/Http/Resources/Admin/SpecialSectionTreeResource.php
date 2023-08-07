<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class SpecialSectionTreeResource extends JsonResource
{

    public function toArray($request)
    {
        return [

            'id' => $this->id,
            // 'position' => $this->position,
            'title' => $this->title,
            'sort' => $this->sort,
            'slug' => $this->slug,
            'tabs' => TabTreeResource::collection($this->tabs),
            'spoilers' => SpoilerTreeResource::collection($this->spoilers)

        ];
    }
}


