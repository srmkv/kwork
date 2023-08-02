<?php
namespace App\Services\Order;

use App\Models\Course\Course;
use App\Models\Course\Flow;
use App\Models\Course\Packet;
use App\Models\Doc;


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

use App\Models\DocOtherType;


class MoreInformationDocumentService
{

    public function calculatePriceIndividual($course)
    {
        $price = 0;
        foreach ($course->flows  as $flow) {
            $price += $flow->packets->sum('default_price');
        }
        return $price;
    }


    public static function  directions($directions, $type)
    {   
        if ($directions == ["all"]) {
            return "all";
        } else {

           
            switch ($type) {
                case 'specialitet':
                    if(count($directions) > 0) {
                        $d  = HigherSpecialitetDirection::find($directions);
                        return $d;
                    }
                    break;
                case 'master':
                    if(count($directions) > 0) {
                        $d  = HigherMasterDirection::find($directions);
                        return $d;
                    }
                    break;

                case 'residency':
                    if(count($directions) > 0) {
                        $d  = HigherResidencyDirection::find($directions);
                        return $d;
                    }
                    break;

                case 'postgraduate':
                    if(count($directions) > 0) {
                        $d  = HigherPostgraduateDirection::find($directions);
                        return $d;
                    }
                    break;

                case 'assistant_intership':
                    if(count($directions) > 0) {
                        $d  = HigherAssistantIntershipDirection::find($directions);
                        return $d;
                    }
                    break;

                case 'backelor':
                    if(count($directions) > 0) {
                        $d  = HigherBackelorDirection::find($directions);
                        return $d;
                    }
                    break;

                case 'specialized_secondary':
                    if(count($directions) > 0) {
                        $d  = SpecializedSecondaryDirection::find($directions);
                        return $d;
                    }
                    break;

                default:
                    return "Неправильно назначены направления";
                    break;
            }


        }
    }
    

    public static function  specialities($specialities, $type)
    {   
        if ($specialities == ["all"] || $specialities == "" ) {
            return "all";
        } else {

           
            switch ($type) {
                case 'specialitet':
                    return HigherSpecialitetSpeciality::find($specialities);
                    break;
                case 'master':
                    return HigherMasterSpeciality::find($specialities);
                    break;
                case 'residency':
                    return HigherResidencySpeciality::find($specialities);
                    break;
                case 'postgraduate':
                    return HigherPostgraduateSpeciality::find($specialities);
                    break;
                case 'assistant_intership':
                    return HigherAssistantIntershipSpeciality::find($specialities);
                    break;
                case 'backelor':
                    return HigherBackelorSpeciality::find($specialities);
                    break;
                case 'specialized_secondary':
                    return SpecializedSecondarySpeciality::find($specialities);
                    break;
                        
                default:
                    return "Неправильно назначены специальности в документе";
                    break;
            }


        }
    }


    public static function otherTypeDocs($other_type_docs)
    {   
        return DocOtherType::find($other_type_docs);
    }


    public static function personalDocument($personal_docs)
    {
        // return collect(\DB::table('docs')->get());
        return Doc::find($personal_docs);
    }

}


