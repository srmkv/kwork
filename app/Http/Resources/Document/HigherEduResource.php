<?php

namespace App\Http\Resources\Document;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Media\ListMediaResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\HigherEdu;

class HigherEduResource extends JsonResource
{
    public function toArray($request)
    {   
        $modelDiplom = HigherEdu::find($this->id);
        return [
            'id' => $this->id,
            'level_education_higher_id' => $this->level_education_higher_id,
            'country_id' => $this->country_id,
            'city_id' => $this->city_id,
            'user_id' => $this->user_id,
            'educational_title' => $this->educational_title,
            'educational_title_id' => $this->educational_title_id,
            'faculty' => $this->faculty,
            'faculty_id' => $this->faculty_id,
            'speciality' => $this->speciality,
            'speciality_id' => $this->speciality_id,
            'direction_id' => $this->direction_id,
            'study_form_id' => $this->study_form_id,
            'complited' => $this->complited,
            'year_ended' => $this->year_ended,
            'serial_number' => $this->serial_number,
            'region_id' => $this->region_id, 
            'media_id' => ListMediaResource::collection($modelDiplom->getMedia('higher_diplom')) 

        ];
    }
}
