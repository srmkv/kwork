<?php

namespace App\Http\Resources\Document;

use App\Http\Resources\General\CountryResource;
use App\Models\General\Country;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Services\UserDocuments\PassportService;
use App\Models\Passport;
class PassportResource extends JsonResource
{

    public function toArray($request)
    {   
        return [
            'passport_id' => $this->id,
            'country_id' => $this->country_id ?? null,
            'last_name' => $this->last_name ?? null,
            'first_name' => $this->first_name ?? null,
            "middle_name" => $this->middle_name ?? null,
            "date_of_birth" => $this->date_of_birth ?? null,
            "serial_number" => $this->serial_number ?? null,
            "issued_by_whom" => $this->issued_by_whom ?? null,
            "date_issue" => $this->date_issue ?? null,
            "subdivision_code" => $this->subdivision_code ?? null,
            "citizenship" => $this->citizenship ?? null,
            "passport_media" => PassportService::getMediaPassport(Passport::find($this->id)) 

        ];
    }
}
