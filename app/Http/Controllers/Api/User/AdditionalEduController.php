<?php
namespace App\Http\Controllers\Api\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AdditionalEdu;
use App\Models\AdditionalEdu\AdditionalSpeciality;
use App\Models\AdditionalEdu\AdditionalDirection;
use App\Traits\Company;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AdditionalEduController extends Controller
{
    public function getFormations(Request $request)
    {   
        $user = Company::currentUserForAction($request);
        $user_id = $user->id;
        $adddional_edu = \DB::table('additional_education')->where('user_id', $user_id)->get();

        if(count($adddional_edu) > 0 ) {
            return collect($adddional_edu);
        }
    }

    public function getFormationById(Request $request, $diplom_id)
    {
        return AdditionalEdu::find($diplom_id);
    }

    public function createFormation(Request $request)
    {
        $user = Company::currentUserForAction($request);
        $adddional_edu = new AdditionalEdu;
        $adddional_edu->user_id = $user->id;
        $adddional_edu->country_id = 1;
        $adddional_edu->save();
        return $adddional_edu;
    }

    public function deleteFormation(Request $request)
    {
        $user = Company::currentUserForAction($request);
        $formation = AdditionalEdu::find($request->edu_id);
        if( !isset($formation)){
            return response()->json([
                "message" => "Образование не найдено..",
                "code" => 404,
            ],404);
        }
        if($user->id == $formation->user_id) {
            $formation->delete();
            return response()->json([
                "message" => "Доп. образование удалено..",
                "code" => 202,
            ],202);
        } else {
            return response()->json([
                "message" => "Вы не можете этого сделать..",
                "code" => 403,
            ],403);
        }
    }


    public function newSpeciality(Request $request)
    {
        $speciality = new AdditionalSpeciality;
        $speciality->title = $request->title;
        $speciality->direction_id = $request->direction_id;
        $speciality->save();
        return $speciality;
    }


    public function newDirection(Request $request)
    {
        $direction = new AdditionalDirection;
        $direction->title = $request->title;
        $direction->save();
        return $direction;
    }

    public function getAllDirections(Request $request)
    {
        $directions = AdditionalDirection::all();
        return $directions;
    }


    public function getAllSpecialities(Request $request)
    {
        $specialities = AdditionalSpeciality::all();
        return $specialities;
    }

    public function getSpeciality(Request $request, $speciality_id)
    {
        $speciality = AdditionalSpeciality::find($speciality_id);
        return $speciality;
    }


    public function getSpecialitiesInDirection(Request $request, $direction_id)
    {
        return \DB::table('additional_specialities')->where('direction_id', $direction_id)->get();
    }

    

    public function editFormation(Request $request)
    {
        $user = Company::currentUserForAction($request);
        $additional_id = $request->additional_id;
        $additional_edu = AdditionalEdu::find($additional_id);

        if(!isset($additional_edu)){
            return response()->json([
                "message" => "Не найдено доп. образование с этим id..",
                "code" => 404,
            ],404);
        }
        if($user->id == $additional_edu->user_id) {
            $additional_edu->update([
                'country_id' => $request->country_id ?? 1,
                'city_id' => $request->city_id ?? null,
                'title_additional' => $request->title_additional ?? null,
                'hours' => $request->hours ?? null,
                'type_edu' => $request->type_edu ?? 1,
                'edu_organization' => $request->edu_organization ?? 1,
                'direction_id' => $request->direction_id ?? null,
                'speciality_id' => $request->speciality_id ?? null,
                'year_start' => $request->start_edu ?? null,
                'year_ended' => $request->end_edu ?? null,
                'additional_serial_number' => $request->additional_serial_number ?? null
            ]);
            $additional_edu->save();
            return $additional_edu;
        } else {
            return response()->json([
                "message" => "Подумайте еще раз..",
                "code" => 404,
            ],404);
        }    
    }
}
