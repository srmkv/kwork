<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeesListResource extends JsonResource
{
    public function toArray($request)
    {   
        // dd($this);
        return [
            "profile_id" => $this->id,
            "user_id" => $this->user_id,
            "avatar"  => $this->avatar,
            "lastname" => $this->lastname,
            "name" => $this->name,
            "middle_name" => $this->middle_name,
            "phone" => $this->phone,
            "email" => $this->email,
            "job_position" => $this->pivot->job_position,
            "date_birthday" => $this->date_birthday,
            "avatarimage" => $this?->avatarimage,
            "role_id"   => $this->pivot->role_id, // !!
            "status"   => $this->pivot->status,
        ];
    }
}
