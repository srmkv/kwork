<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class FlowListResource extends JsonResource
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
            'type_id' => $this->type_id,
            'start' => $this->start,
            'end' => $this->end,
            'days_after_by' => $this->days_after_by,
            'study_form_id' => $this->study_form_id,
            'sections' => SectionCourseResource::collection($this->sections),
            'packets' => PacketListResource::collection($this->packets)
        ];
    }
}
