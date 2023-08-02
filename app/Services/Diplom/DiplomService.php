<?php
namespace App\Services\Diplom;


use App\Models\HigherEdu\HigherResidencyDirection;
use App\Models\HigherEdu\HigherResidencySpeciality;
use App\Models\HigherEdu\HigherSpecialitetSpeciality;
use App\Models\HigherEdu\HigherSpecialitetDirection;
use App\Models\HigherEdu\HigherPostgraduateSpeciality;
use App\Models\HigherEdu\HigherPostgraduateDirection;
use App\Models\HigherEdu\HigherAssistantIntershipSpeciality;
use App\Models\HigherEdu\HigherAssistantIntershipDirection;
use App\Models\HigherEdu\HigherBackelorSpeciality;
use App\Models\HigherEdu\HigherBackelorDirection;
use App\Models\HigherEdu\HigherMasterSpeciality;
use App\Models\HigherEdu\HigherMasterDirection;
use App\Models\SpecializedSecondaryEdu\SpecializedSecondaryDirection;
use App\Models\SpecializedSecondaryEdu\SpecializedSecondarySpeciality;





class DiplomService
{

    public static function getParamsDiplom($level_id, $id, $param)
    {   
        switch ($level_id) {
            case 1:
                if($param == "speciality") {
                    if(HigherBackelorSpeciality::find($id) == null) {
                        break;
                    } else {

                        return HigherBackelorSpeciality::find($id)->title; 

                        // сделать так
                        // return optional(HigherBackelorSpeciality::find($id))->title;
                    }
                }
                if($param == "direction") {
                    if(HigherBackelorDirection::find($id) == null ) {
                        break;
                    }
                    return HigherBackelorDirection::find($id)->title;
                }
                break;
            case 2:
                if($param == "speciality") {
                    if(HigherMasterSpeciality::find($id) == null) {

                        break;
                    }
                    return HigherMasterSpeciality::find($id)->title;
                }
                if($param == "direction") {

                    if(HigherMasterDirection::find($id) == null ){
                        break;
                    }

                    return HigherMasterDirection::find($id)->title;
                }
                break;
            case 3:
                if($param == "speciality") {
                    if(HigherSpecialitetSpeciality::find($id) == null) {
                        break;
                    }
                    return HigherSpecialitetSpeciality::find($id)->title;
                }
                if($param == "direction") {
                    if(HigherSpecialitetDirection::find($id) == null) {
                        break;
                    }
                    return HigherSpecialitetDirection::find($id)->title;
                }
                break;
            case 4:
                if($param == "speciality") {
                    if( HigherResidencySpeciality::find($id) == null) {
                        break;
                    }
                    return HigherResidencySpeciality::find($id)->title;
                }
                if($param == "direction") {

                    if(HigherResidencyDirection::find($id) == null) {
                        break;
                    }
                    return HigherResidencyDirection::find($id)->title;
                }
                break;

            case 5:
                if($param == "speciality") {
                    if(HigherPostgraduateSpeciality::find($id) == null) {
                        break;
                    }
                    return HigherPostgraduateSpeciality::find($id)->title;
                }
                if($param == "direction") {
                    if( HigherPostgraduateDirection::find($id) == null) {

                        break;
                    }
                    return HigherPostgraduateDirection::find($id)->title;
                }

                break;
            case 6:
                if($param == "speciality") {
                    if(HigherAssistantIntershipSpeciality::find($id) == null) {

                        break;
                    }
                    return HigherAssistantIntershipSpeciality::find($id)->title;
                }
                if($param == "direction") {
                    if(HigherAssistantIntershipDirection::find($id) == null) {
                        break;
                    }
                    return HigherAssistantIntershipDirection::find($id)->title;
                }

                break;


            default:
                return 'Неожиданный тип образоавния';
                break;
        }
    }

    public static function getParamsSpecializedSecondaryDiplom($id, $param)
    {   
        // dd($id);
        if($param == "speciality") {
            if ( SpecializedSecondarySpeciality::find($id) == null) {

                return '';
            }
            return SpecializedSecondarySpeciality::find($id)->title;
        }

        if($param == "direction") {
            if(SpecializedSecondaryDirection::find($id) == null) {
               return '';
            }
            return SpecializedSecondaryDirection::find($id)->title;
        }
    }





}


