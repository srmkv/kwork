<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\User\IndividualResource;

class UserProfileResource extends JsonResource
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
            'user_id' => $this->id,
            'phone' => $this->phone,
            'verified' => $this->verified,
            'profile' => $this->individualProfile,
            'type_user' => $this->name
        ];
    }
}
