<?php

namespace App\Http\Resources\Course;

use App\Services\FlowService;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TagRefinementResource extends JsonResource
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
            'title' => $this->title,
        ];
    }
}
