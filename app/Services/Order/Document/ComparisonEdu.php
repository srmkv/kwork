<?php

namespace App\Services\Order\Document;

use App\Models\Course\NeededSpeciality;
use App\Models\Course\Course;
use App\Models\HigherEdu;
use App\Models\SpecializedSecondaryEdu;

use App\Services\Order\OrderSpecialityService;

class ComparisonEdu
{

    public function __construct(OrderSpecialityService $orderSpeciality)
    {
        $this->orderSpeciality = $orderSpeciality;
    }

    public function correctionStatus($user_documents, $edu_docs)
    {   
        if($user_documents == null ){
            return false;
        } 

        // dd($edu_docs);

        $edu_docs = $edu_docs['array_documents'];
        
        foreach ($edu_docs as  $edu_doc) {
            $level_id = $this->orderSpeciality->checkLevel($edu_doc['name']);
            $need_directions = $edu_doc['directions'];
            $need_specialities = $edu_doc['specialities'];

            $user_diplom_comparison = ($level_id == 7) ?  
                    SpecializedSecondaryEdu::find($user_documents['secondary_id']) : 
                    HigherEdu::find($user_documents['higher_id']);
            
            if($this->sitautionOrder($need_directions, $need_specialities, $user_diplom_comparison, $level_id)){
                return true;
            } else {
                return false;
            }
        }
    }

    // какова ситуация?
    public function sitautionOrder(
        $need_directions,
        $need_specialities, 
        $user_diploms,  
        $level_id
    )
    {   

        // 0. Любые  направления и любые специальности в edu_doc
        if($need_directions == ["all"] && $need_specialities == ["all"] && $user_diploms->isNotEmpty()) {
                return true;
        }

        // 1. Определенные направления, но любые специальности
        if($need_directions != ["all"] && ($need_specialities == ["all"] || $need_specialities == [] ) 
                                       && $user_diploms->isNotEmpty() 
                                       &&  $this->checkAnyDirections($user_diploms, $level_id, $need_directions)) {
            return true;
        }
        
        // 2. конкретные специальности
        if( $need_directions != ["all"] && $need_specialities != ["all"]  && 
            $user_diploms->isNotEmpty() && $this->checkSpecialities($user_diploms, $level_id, $need_specialities)) {
            return true;
        }
        return false;
    }

    // проверяем только на наличие нужных направлений в уровне образования
    public function checkAnyDirections($diploms, $level_id, $need_directions)
    {
        foreach ($diploms as $diplomIndex => $diplom) {
            if(in_array($diplom->direction_id, $need_directions)){
                return true;
            } 
        }
        return false;
    }

    // совпадает ли специальность?
    public function checkSpecialities($diploms, $level_id, $need_specialities)
    {   
        foreach ($diploms as $diplomIndex => $diplom) {
            if(in_array($diplom->speciality_id, $need_specialities)){
                return true;
            } 
        }
        return false;
    }

    // проверка на наличие нужного доп. документа
    public function checkOtherTypeDocument($need_other_types, $user_other_docs) 
    {   
        $successArr = [];
        foreach ($user_other_docs as $docIndex => $other_doc) {
            if(in_array($other_doc->type_id, $need_other_types) ) {
                array_push($successArr, $other_doc->type_id);
            }
        }
        $successArr = ($successArr == []) ? false : $successArr;
        return $successArr;
    }

}