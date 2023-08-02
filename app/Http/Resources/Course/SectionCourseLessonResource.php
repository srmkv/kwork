<?php

namespace App\Http\Resources\Course;

use App\Http\Resources\CourseSectionThemeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionCourseLessonResource extends JsonResource
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
            'description' => $this->description,
            'date' => $this->date,
            'hours' => $this->hours,
            'study_form_id' => $this->study_form_id,
            'type_id' => $this->type_id,
            'show_task_answers' => $this->show_task_answers,
            'show_materials' => $this->show_materials,
            'show_comments' => $this->show_comments,
            'show_teachers' => $this->show_teachers,
            'link' => $this->link,
            'address' => $this->address,
            'themes' => CourseSectionThemeResource::collection($this->themes),
            'teachers' => TeacherSectionResource::collection($this->teachers),

        ];
    }
}
