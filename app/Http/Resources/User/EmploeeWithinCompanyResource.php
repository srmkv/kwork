<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Services\User\EmployeeService;

class EmploeeWithinCompanyResource extends JsonResource
{

    public function toArray($request)
    {   
        return [
            "profile_id" => $this->id,
            "user_id" => $this->user_id,
            "avatar"  => $this->avatar,
            "lastname" => $this->lastname,
            "name" => $this->name,
            "middle_name" => $this->middle_name,
            "phone" => $this->phone,
            "email" => $this->email,
            "date_birthday" => $this->date_birthday,
            "avatarimage" => $this?->avatarimage,
            "role_id"   => $request->role_id,
            "job_position" => $request->job_position
        ];


    }
}
