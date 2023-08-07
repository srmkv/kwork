<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\User\EmployeeService;

class BusinessStudentsCourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {   

        dd($this);
        return [

            // 'profile_id' => $this['ids'], 
            // 'full_name' => EmployeeService::fioProfile($this->id),
            // 'status_documents' 
        ];
    }
}
