<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class FormsInSpoilerResource extends JsonResource
{

    public function toArray($request)
    {   
        $collectionForms = collect();
        $forms_1 =   collect($this->formsDataOrg);
        $forms_2 =   collect($this->formsOrgUnit);
        $forms_3 =   collect($this->formsEduProgram);
        $forms_4 =   collect($this->formsMaterialEquipment);
        $forms_5 =   collect($this->formsFellowshipMeasure);
        $forms_6 =   collect($this->formsDataDirectorEdu);
        $forms_7 =   collect($this->formsDataDirector);
        $forms_8 =   collect($this->formsDocument);
        $forms_9 =   collect($this->formsAccesibleEnv);
        $forms_10 =  collect($this->formsEducation);
        $forms_11 =  collect($this->formsInternationalCooperation);
        $forms_12 =  collect($this->formsFinancialSource);
        $forms_13 =  collect($this->formsEconomicActivityPlan);
        $forms_14 =  collect($this->formsSpeciality);
        $forms_15 =  collect($this->formsVacantPlaces);

        foreach ($forms_1 as $form) {   
            $collectionForms->push($form);
        }
        foreach ($forms_2 as $form) {
            $collectionForms->push($form);
        }
        foreach ($forms_3 as $form) {
            $collectionForms->push($form);
        }
        foreach ($forms_4 as $form) {
            $collectionForms->push($form);
        }
        foreach ($forms_5 as $form) {
            $collectionForms->push($form);
        }

        foreach ($forms_6 as $form) {
            $collectionForms->push($form);
        }
        foreach ($forms_7 as $form) {
            $collectionForms->push($form);
        }
        foreach ($forms_8 as $form) {
            $collectionForms->push($form);
        }
        foreach ($forms_9 as $form) {
            $collectionForms->push($form);
        }
        foreach ($forms_10 as $form) {
            $collectionForms->push($form);
        }
        foreach ($forms_11 as $form) {
            $collectionForms->push($form);
        }
        foreach ($forms_12 as $form) {
            $collectionForms->push($form);
        }
        foreach ($forms_13 as $form) {
            $collectionForms->push($form);
        }
        foreach ($forms_14 as $form) {
            $collectionForms->push($form);
        }
        foreach ($forms_15 as $form) {
            $collectionForms->push($form);
        }

        return [

            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'position' => $this->position,
            'admin_section_id' => $this->admin_section_id,
            'description' => $this->description,
            'forms_in_spoiler' => $collectionForms

        ];
    }
}
