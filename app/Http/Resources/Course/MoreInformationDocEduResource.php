<?php

namespace App\Http\Resources\Course;

use App\Services\Order\MoreInformationDocumentService;

use Illuminate\Http\Resources\Json\JsonResource;

class MoreInformationDocEduResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {   

        // dd($this);
        

        return [
            "name" => $this["name"],
            "directions" => MoreInformationDocumentService::directions($this["directions"], $this["name"]),
            "specialities" => MoreInformationDocumentService::specialities($this["specialities"], $this["name"])
        ];
    }
}
