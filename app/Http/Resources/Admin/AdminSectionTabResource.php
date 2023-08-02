<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminSectionTabResource extends JsonResource
{

    public function toArray($request)
    {   

        // dd($this);
        return [

            'tab_id' => $this->id,
            'title' => $this->title,
            'admin_section_id' => $this->admin_section_id,
            'spoilers' => FormsInSpoilerResource::collection($this->spoilers)

        ];
    

    }

}
