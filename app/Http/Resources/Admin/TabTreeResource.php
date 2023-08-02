<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class TabTreeResource extends JsonResource
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
            'tab_id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'spoilers' => SpoilerTreeResource::collection($this->spoilers)
        ];
    }
}
