<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\User;
use App\Models\Profiles\ProfileIndividual;

use App\Http\Resources\User\BusinessResource;
use App\Http\Resources\User\SelfEmployedResource;

use App\Models\Profiles\ProfileBuiseness;
use App\Models\Profiles\ProfileSelfEmployed;
use App\Services\User\EmployeeService;

class MemberCompaniesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {   


        $type = $this->profiles_individuals_type;

        if($type == 'business'){

            $company = BusinessResource::make(ProfileBuiseness::find($this->profileble_id));
        } 

        if($type == 'ip') 
        {
            $company = SelfEmployedResource::make(ProfileSelfEmployed::find($this->profileble_id));
        }
        
        return [
            'company_id' => $this->profileble_id,
            'type'  => $type,
            'user_who_invited' => EmployeeService::fioProfile($this->profile_who_invited),
            'invitation_date' => $this->invitation_date,
            'status_invite' => $this->status,
            'job_position' => $this->job_position_who,
            'company' => $company ,


        ];


    }
}
