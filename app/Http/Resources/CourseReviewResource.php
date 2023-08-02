<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $process = $this->author->courseProcesses()->where('course_id', $this->course_id)->first();
        return [
            'id' => $this->id,
            'date' => $this->created_at->format('Y-m-d H:i:s'),
            'rating' => $this->rating,
            'text' => $this->text,
            'author' => [
                'id' => $this->author?->id,
                'name' => $this->author?->individualProfile?->name,
                'photo' => $this->author?->individualProfile?->avatarimage,
                'course_process' => !$process || !$process->type() ? null : $process->type()->get(['id', 'name']),
            ],
            'replies' => CourseReviewResource::collection($this->childsRecursive),
        ];
    }
}
