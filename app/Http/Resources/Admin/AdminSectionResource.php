<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;



class AdminSectionResource extends JsonResource
{

    public function toArray($request)
    {   
        // dd($this->tabs);
        return [

            'id' => $this->id,
            'title' => $this->title,
            'sort' => $this->sort,
            'tabs' => AdminSectionTabResource::collection($this->tabs),
            'spoiler' => FormsInSpoilerResource::collection($this->spoilers)
        ];
    }
}
