<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Services\FlowService;
use App\Models\Course\Course;
class ListFlowsStudentsResource extends JsonResource
{   

    public function toArray($request)
    {   
        // dd($this);
        $course = Course::find(FlowService::getCourseId($this->id));

        return [

            'flow_id' => $this->id,
            'title' => $course->name,
            'duration' => [
                'hours' => $course->academic_hours,
                'days' => $course->academic_days
            ],
            'start' => $this->start,
            'end'   => $this->end
        ]; 
    }
}
