<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;
class IndividualResource extends JsonResource
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

            'ava' => $this->avatar,
            'lastname' => $this->lastname,
            'name' => $this->name,
            'middle_name' => $this->middle_name,
            'phone' => $this->phone,
            'email' => $this->email,
            // 'date_birthday' =>  new Carbon($this->date_birthday)
            'date_birthday' =>  $this->date_birthday


        ];
    }
}
