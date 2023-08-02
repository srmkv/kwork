<?php
namespace App\Http\Controllers\General;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//временный контролер для наполнения специальностей
use App\Models\HigherEdu;
use App\Models\HigherEdu\HigherEduSpeciality;
use App\Models\HigherEdu\HigherEduDirection;
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
use App\Models\SpecializedSecondaryEdu;
use App\Models\SpecializedSecondaryEdu\SpecializedSecondarySpeciality;
use App\Models\SpecializedSecondaryEdu\SpecializedSecondaryDirection;

//any
use App\Services\Payment\InstallmentService;
use App\Models\Payment\InstallmentProcess;
use App\Services\Order\OrderCommonService;

class TempController extends Controller
{
    public $temp_direction_hight_id;
    public $temp_direction_secondary_id;
    public $temp_direction_id;

    public function __construct(InstallmentService $installmentAction)
    {
        $this->installmentAction = $installmentAction;
    }

    public function insertSpecialitiesAndDirectionsData(Request $request)
    {
        $higherData = \DB::table('temp-db.higher_specialities')->get();
        $secondaryData = \DB::table('temp-db.table')->get();
        //НАПОЛНИТЬ  СПЕЦИАЛЬНОСТИ (ВЫШКИ)
        // В ЦИКЛЕ НАПОЛНЯЮТСЯ ДАННЫЕ
        foreach ($higherData as $index => $data) {
            if($data->field_2 == null ) {
                continue;
            }

            $word_title = explode(" ", $data->field_2)[0];    
            //находим направление
            if( $this->starts_with_upper($word_title) == 1 ) {

                $direction = new HigherEduDirection;
                $direction->title = $data->field_2;
                $direction->save();
                $this->temp_direction_id = $direction->id;
                //дальше сохраняем спецухи с этим направлением
                continue;
            } else {
                $speciality = new HigherEduSpeciality;
                $speciality->speciality_code = $data->field_1;
                $speciality->direction_id = $this->temp_direction_id;
                $speciality->title = $data->field_2;
                $speciality->qualification = $data->field_3;
                $speciality->save();
            }
        }

        //НАПОЛНИТЬ СРЕДНИЕ СПЕЦИАЛЬНОСТИ
        foreach ($secondaryData as $index => $data) {
            if($data->field_2 == null ) {
                continue;
            }
            
            $word_title = explode(" ", $data->field_2)[0];    
            //находим направление
            if( $this->starts_with_upper($word_title) == 1 ) {
                $direction = new SpecializedSecondaryDirection;
                $direction->title = $data->field_2;
                $direction->save();
                $this->temp_direction_secondary_id = $direction->id;
                //дальше сохраняем спецухи с этим направлением
                continue;
            } else {
                $speciality = new SpecializedSecondarySpeciality;
                $speciality->speciality_code = $data->field_1;
                $speciality->direction_id = $this->temp_direction_secondary_id;
                $speciality->title = $data->field_2;
                $speciality->qualification = $data->field_3;
                $speciality->save();
            }
            
        }
        return 'success !'; 
    }

    public function insertResidencySpeciality(Request $request)
    {
        $residencyData = \DB::table('temp-db.higher_residency')->get();
        foreach ($residencyData as $index => $data) {
            if($data->field_3 == null ) {
                continue;
            }
            $word_title = explode(" ", $data->field_2)[0];    
            //находим направление
            if( $this->starts_with_upper($word_title) == 1 ) {
                $direction = new HigherResidencyDirection;
                $direction->title = $data->field_2;
                $direction->save();
                $this->temp_direction_id = $direction->id;
                //дальше сохраняем спецухи с этим направлением
                continue;
            } else {
                $speciality = new HigherResidencySpeciality;
                $speciality->speciality_code = $data->field_1;
                $speciality->direction_id = $this->temp_direction_id;
                $speciality->title = $data->field_2;
                $speciality->qualification = $data->field_3;
                $speciality->save();
            }
        }
        return 'complete success!';
    }


    public function insertSpecialitetSpeciality(Request $request)
    {
        $dataSpecialitet = \DB::table('temp-db.higher_specialitet')->get();

        // В ЦИКЛЕ НАПОЛНЯЮТСЯ ДАННЫЕ
        foreach ($dataSpecialitet as $index => $data) {

            if($data->field_2 == null ) {
                continue;
            }

            $word_title = explode(" ", $data->field_2)[0];    
            //находим направление
            if( $this->starts_with_upper($word_title) == 1 ) {

                $direction = new HigherSpecialitetDirection;
                $direction->title = $data->field_2;
                $direction->save();
                $this->temp_direction_id = $direction->id;
                //дальше сохраняем спецухи с этим направлением
                continue;
            } else {

                $speciality = new HigherSpecialitetSpeciality;
                $speciality->speciality_code = $data->field_1;
                $speciality->direction_id = $this->temp_direction_id;
                $speciality->title = $data->field_2;
                $speciality->qualification = $data->field_3;
                $speciality->save();
            }   
        }
        return 'success !'; 
    }


    public function insertPostgraduateSpeciality(Request $request)
    {
        $dataPostgraduate = \DB::table('temp-db.higher_postgraduate')->get();

        // В ЦИКЛЕ НАПОЛНЯЮТСЯ ДАННЫЕ
        foreach ($dataPostgraduate as $index => $data) {

            if($data->field_2 == null ) {
                continue;
            }

            $word_title = explode(" ", $data->field_2)[0];    
            //находим направление
            if( $this->starts_with_upper($word_title) == 1 ) {

                $direction = new HigherPostgraduateDirection;
                $direction->title = $data->field_2;
                $direction->save();
                $this->temp_direction_id = $direction->id;
                //дальше сохраняем спецухи с этим направлением
                continue;
            } else {

                $speciality = new HigherPostgraduateSpeciality;
                $speciality->speciality_code = $data->field_1;
                $speciality->direction_id = $this->temp_direction_id;
                $speciality->title = $data->field_2;
                $speciality->qualification = $data->field_3;
                $speciality->save();
            }   
        }
        return 'success !'; 
    }

    

    public function insertAssistantIntershipSpeciality(Request $request)
    {
        $dataAssistant = \DB::table('temp-db.higher_postgraduate')->get();

        // В ЦИКЛЕ НАПОЛНЯЮТСЯ ДАННЫЕ
        foreach ($dataAssistant as $index => $data) {

            if($data->field_2 == null ) {
                continue;
            }

            $word_title = explode(" ", $data->field_2)[0];    
            //находим направление
            if( $this->starts_with_upper($word_title) == 1 ) {

                $direction = new HigherAssistantIntershipDirection;
                $direction->title = $data->field_2;
                $direction->save();
                $this->temp_direction_id = $direction->id;
                //дальше сохраняем спецухи с этим направлением
                continue;
            } else {
                $speciality = new HigherAssistantIntershipSpeciality;
                $speciality->speciality_code = $data->field_1;
                $speciality->direction_id = $this->temp_direction_id;
                $speciality->title = $data->field_2;
                $speciality->qualification = $data->field_3;
                $speciality->save();
            }
            
        }
        return 'success !'; 
    }

    public function insertBackelorSpeciality(Request $request)
    {
        $dataBackelor = \DB::table('temp-db.higher_backelor')->get();
        // В ЦИКЛЕ НАПОЛНЯЮТСЯ ДАННЫЕ
        foreach ($dataBackelor as $index => $data) {
            if($data->field_2 == null ) {
                continue;
            }
            $word_title = explode(" ", $data->field_2)[0];    
            //находим направление
            if( $this->starts_with_upper($word_title) == 1 ) {
                $direction = new HigherBackelorDirection;
                $direction->title = $data->field_2;
                $direction->save();
                $this->temp_direction_id = $direction->id;
                //дальше сохраняем спецухи с этим направлением
                continue;
            } else {

                $speciality = new HigherBackelorSpeciality;
                $speciality->speciality_code = $data->field_1;
                $speciality->direction_id = $this->temp_direction_id;
                $speciality->title = $data->field_2;
                $speciality->qualification = $data->field_3;
                $speciality->save();
            }
            
        }
        return 'success !'; 
    }



    public function insertMasterSpeciality(Request $request)
    {
        $dataMaster = \DB::table('temp-db.higher_master')->get();

        // В ЦИКЛЕ НАПОЛНЯЮТСЯ ДАННЫЕ
        foreach ($dataMaster as $index => $data) {

            if($data->field_2 == null ) {
                continue;
            }

            $word_title = explode(" ", $data->field_2)[0];    
            //находим направление
            if( $this->starts_with_upper($word_title) == 1 ) {

                $direction = new HigherMasterDirection;
                $direction->title = $data->field_2;
                $direction->save();
                $this->temp_direction_id = $direction->id;
                //дальше сохраняем спецухи с этим направлением
                continue;
            } else {

                $speciality = new HigherMasterSpeciality;
                $speciality->speciality_code = $data->field_1;
                $speciality->direction_id = $this->temp_direction_id;
                $speciality->title = $data->field_2;
                $speciality->qualification = $data->field_3;
                $speciality->save();
            }
            
        }
        return 'success !'; 
    }

    public function starts_with_upper($str) {
            $chr = mb_substr ($str, 1, 2, "UTF-8");
            return mb_strtolower($chr, "UTF-8") != $chr;
    }

    public function anyTests(Request $request)
    {

        // return dd(config('tinkoff.terminal_key'));

        // return  $this->installmentAction->checkIsPaidInstallment(InstallmentProcess::find(15));
        // dd($this->installmentAction->checkIsPaidInstallment(InstallmentProcess::find(14)));

        // dd(OrderCommonService::STATUS_PAID);
    }


}


