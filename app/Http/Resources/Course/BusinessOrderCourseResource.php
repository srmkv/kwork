<?php

namespace App\Http\Resources\Course;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Course\Flow;
use App\Models\Course\Course;
use App\Models\Course\Packet;

use App\Services\CourseSectionLessonService;
use App\Traits\CourseTrait;

class BusinessOrderCourseResource extends JsonResource
{
    public function toArray($request)
    {   
        $flow = Flow::find($this['flow_id']);
        $course = Course::find($this['id']);
        
        return [
            'id' => $this['id'],
            'flow_id' => $this['flow_id'],
            'slug' => $course->slug,
            'title' => Course::find($this['id'])->name,
            'start_edu' => $flow->start ?? null,
            'end_edu' => $flow->end ?? null,
            'direction' =>  CourseTrait::getDirection($this['id']) ?? '',
            'count_students'  => count($this['students']),
            'price' =>  Packet::find($this['packet_id'])->default_price,
            'status' => 'awaiting payment',
            'speciality' => CourseTrait::getSpeciality($this['id']) ?? '',
            'full_price_for_this_course' => Packet::find($this['packet_id'])->default_price * count($this['students']),
            'study_form' =>  \DB::table('study_forms')->where('id', $flow->study_form_id)->first()->title ?? 'Не установлено', 
            'locations' =>  CourseSectionLessonService::getLocationsInFlow($flow->id),
            'received_documents' => CourseTrait::getReceivedDocuments($this['id']),

            'duration' => [
                'hours' => $course->academic_hours,
                'days' => $course->academic_days
            ],

        ];
    }
}
