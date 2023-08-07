<?php

namespace App\Http\Resources\Course;

use App\Http\Resources\CourseSectionThemeResource;
use App\Models\Course\CourseSectionLesson;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionCourseResource extends JsonResource
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
            'lessons' => SectionCourseLessonResource::collection($this->lessons)

        ];
    }
}
