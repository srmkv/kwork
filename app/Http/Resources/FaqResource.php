<?php

namespace App\Http\Resources;

use App\Models\Course\FaqQuestion;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
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
            'answer' => $this->answer,
            'position' => $this->position,
            'contains_subsections' => $this->contains_subsections,
            'questions' => FaqQuestionResource::collection($this->questions)
        ];
    }
}
