<?php

namespace App\Services\Order;

use App\Models\Course\NeededSpeciality;
use App\Models\Course\Course;
use App\Models\HigherEdu;
use App\Models\SpecializedSecondaryEdu;


class OrderSpecialityService
{

    // 1   Бакалавриат backelor
    // 2   Магистратура    master
    // 3   Специалитет specialitet
    // 4   Ординатура  residency
    // 5   Аспирантура postgraduate
    // 6   Ассистентура-стажировка assistant_intership

    public $orderResponse;

    public function checkLevel($level_edu)
    {   
        switch ($level_edu) {
            case 'backelor':
                $level_id = 1;
                break;
            case 'master':
                $level_id = 2;
                break;
            case 'specialitet':
                $level_id = 3;
                break;
            case 'residency':
                $level_id = 4;
                break;
            case 'postgraduate':
                $level_id = 5;
                break;
            case 'assistant_intership':
                $level_id = 6;
                break;
            case 'specialized_secondary':
                $level_id = 7;
                break;
            default:
                return 399;
                break;
        }

        return $level_id;
    }



    public function diplomsComparison($course, $user, $edu_doc, $documentId, $need_other_types, $index, $crutchPress)
    {   
        $level_edu = $edu_doc['name'];
        // смешанные уровни образоавния, тут есть высшее/среднеспец/доп
        $level_id = $this->checkLevel($level_edu);        
        $need_directions = $edu_doc['directions'];
        $need_specialities = $edu_doc['specialities'];
        $user_other_docs = $user->otherDocuments;


        // СРЕДНЕСПЕЦИАЛЬНОЕ ОБРАЗОВАНИЕ
        if($level_id == 7) {

            // $user_diplom_comparison  =  collect($user_diploms->where('level_education_higher_id', $level_id));
            // $user_diplom_comparison_specialities  = $user_diplom_comparison->where('speciality_id', $need_specialities);

            // return $this->compareSpecializedSecondary($need_directions, $need_specialities);

            // return $this->orderResponse = [
            //     "document_id" => $documentId,
            //     "status" => "matched",
            // ];

            // dd($crutchPress);
            if($crutchPress) {
                // $user_diploms = HigherEdu::find($crutchPress);

                // $user_diplom_comparison  =  collect($user_diploms->where('level_education_higher_id', $level_id));
                // // dd($user_diplom_comparison);
                // // дипломы совпавшие по конкретным специальностям
                // $user_diplom_comparison_specialities  = $user_diplom_comparison->where('speciality_id', $need_specialities);


                $user_diplom_comparison = SpecializedSecondaryEdu::find($crutchPress);

                // dd($user_diplom_comparison);


            } else {

                // $user_diploms = $user->higherDiploms;
                // // дипломы юзера с подходящим УРОВНЕМ образования
                // $user_diplom_comparison  =  collect($user_diploms->where('level_education_higher_id', $level_id));

                $user_diplom_comparison = \DB::table('specialized_secondary_education')->where('user_id', $user->id)->get();
            }


            

            switch ($this->sitautionOrder(
                $need_directions,
                $need_specialities,
                $user_diplom_comparison,
                $need_other_types,
                $user_other_docs,
                $level_id
            )){
                case 'anyAll':
                    return $this->orderResponse = [
                        "document_id" => $documentId,
                        "status" => "matched_any_all",
                        "user_diploms" => $user_diplom_comparison->pluck('id')
                    ];

                    if($crutchPress) {
                        return 1;
                    }

                    break;
                case 'anySpecialities' :
                    return $this->orderResponse = [
                        "document_id" => $documentId, 
                        "status" => "matched_directions",
                        "user_diploms" => $user_diplom_comparison->pluck('id') 
                    ];

                    if($crutchPress) {
                        return 1;
                    }
                    break;
                case 'matchSpeciality' :

                    return $this->orderResponse = [
                        "document_id" => $documentId,
                        "status" => "matched_specialities",
                        "user_diploms" => HigherEdu::whereIn('speciality_id',  $need_specialities)->pluck('id')
                    ];


                    if($crutchPress) {
                        return 1;
                    }

                    break;
                default:
                    if($crutchPress) {
                        return 0;
                    }
                    
                    return $this->orderResponse = [
                        "document_id" => $documentId,
                        "type" => "specialized_secondary", 
                        "status"  => "unmatched",
                    ];

  


                    break;
            }


        } 


        if($crutchPress) {
            $user_diploms = HigherEdu::find($crutchPress);

            $user_diplom_comparison  =  collect($user_diploms->where('level_education_higher_id', $level_id));
            // dd($user_diplom_comparison);
            // дипломы совпавшие по конкретным специальностям
            $user_diplom_comparison_specialities  = $user_diplom_comparison->where('speciality_id', $need_specialities);


        } else {

            $user_diploms = $user->higherDiploms;
            // дипломы юзера с подходящим УРОВНЕМ образования
            $user_diplom_comparison  =  collect($user_diploms->where('level_education_higher_id', $level_id));
        }





        // дипломы совпавшие по конкретным специальностям
        // $user_diplom_comparison_specialities  = $user_diplom_comparison->where('speciality_id', $need_specialities); 



        


        switch ($this->sitautionOrder(
            $need_directions,
            $need_specialities,
            $user_diplom_comparison,
            $need_other_types,
            $user_other_docs,
            $level_id
        )){
            case 'anyAll':
                $this->orderResponse = [
                    "document_id" => $documentId,
                    "status" => "matched_any_all",
                    "user_diploms" => $user_diplom_comparison->pluck('id')
                ];

                if($crutchPress) {
                    return 1;
                }

                break;
            case 'anySpecialities' :
                $this->orderResponse = [
                    "document_id" => $documentId, 
                    "status" => "matched_directions",
                    "user_diploms" => $user_diplom_comparison->pluck('id') 
                ];

                if($crutchPress) {
                    return 1;
                }
                break;
            case 'matchSpeciality' :

                $this->orderResponse = [
                    "document_id" => $documentId,
                    "status" => "matched_specialities",
                    "user_diploms" => HigherEdu::whereIn('speciality_id',  $need_specialities)->where('user_id', $user->id)->pluck('id')
                ];


                if($crutchPress) {
                    return 1;
                }

                break;
            default:
                $this->orderResponse = [
                    "document_id" => $documentId, 
                    "status"  => "unmatched",
                ];

                if($crutchPress) {
                    return 0;
                }


                break;
        }
        
        return $this->orderResponse;
    }



    // какова ситуация?
    public function sitautionOrder($need_directions, $need_specialities, $user_diploms, $need_other_types, $user_other_docs, $level_id)
    {   

        // 0. Любые  направления и любые специальности в edu_doc
        if($need_directions == ["all"] && $need_specialities == ["all"] && $user_diploms->isNotEmpty()) {
                return 'anyAll';
        }

        // 1. Определенные направления, но любые специальности
        if($need_directions != ["all"] && ($need_specialities == ["all"] || $need_specialities == [] ) 
                                       && $user_diploms->isNotEmpty() 
                                       &&  $this->checkAnyDirections($user_diploms, $level_id, $need_directions)) {

            return 'anySpecialities';
        }
        
        // 2. конкретные специальности
        if( $need_directions != ["all"] && $need_specialities != ["all"]  && 
            $user_diploms->isNotEmpty() && $this->checkSpecialities($user_diploms, $level_id, $need_specialities)) {

            return 'matchSpeciality';

        }

    }

    // проверяем только на наличие нужных направлений в уровне образования
    public function checkAnyDirections($diploms, $level_id, $need_directions)
    {
        foreach ($diploms as $diplomIndex => $diplom) {
            // dd($diploms);
            if(in_array($diplom->direction_id, $need_directions)){
                return true;
            } 
        }

        return false;
    }

    // совпадает ли специальность?
    public function checkSpecialities($diploms,$level_id, $need_specialities)
    {
        foreach ($diploms as $diplomIndex => $diplom) {
            // dd($diploms);
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


    public function compareSpecializedSecondary($need_directions, $need_specialities) 
    {
        // return $need_directions;
    }




    // юзер прожимает чекбоксы в заявке

    public function pressUserHigher($direction, $specialities, $level_id, $user, $higher_ids)
    {

    }



}