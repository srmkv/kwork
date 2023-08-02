<?php

namespace App\Http\Controllers\Api\User;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SpecializedSecondaryEdu;
use App\Models\SpecializedSecondaryEdu\SpecializedSecondarySpeciality;
use App\Models\SpecializedSecondaryEdu\SpecializedSecondaryDirection;
use App\Traits\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserDocuments\StatusDocService;
use App\Services\UserDocuments\SpecializedSecondaryService;

class SpecializedSecondaryEduController extends Controller
{   
    public function __construct(SpecializedSecondaryService $specializedSecondaryAction)
    {
        $this->specializedSecondaryAction = $specializedSecondaryAction;
    }

    public function getSchools(Request $request)
    {   
        $user = Company::currentUserForAction($request);
        return $this->specializedSecondaryAction->getDiploms($user->id);
    }

    public function getSchoolById(Request $request, $school_id)
    {
        return SpecializedSecondaryEdu::find($school_id);
    }

    public function createSchool(Request $request)
    {
        $user = Company::currentUserForAction($request);
        $school = new SpecializedSecondaryEdu;
        $school->user_id = $user->id;
        $school->country_id = 1;
        // $school->status_doc =  collect([
        //     StatusDocService::DOC_STATUS_DEFAULT
        // ]);
        $school->save();
        return $school;
    }

    public function deleteSchool(Request $request)
    {
        $user = Company::currentUserForAction($request);
        $school = SpecializedSecondaryEdu::find($request->school_id);
        return $this->specializedSecondaryAction->delete($school, $user->id);
    }


    public function editSchool(Request $request)
    {
        $user = Company::currentUserForAction($request);
        $school_id = $request->school_id;
        $school = SpecializedSecondaryEdu::find($school_id);
        if( !isset($school)) {
            return response()->json([
                "message" => "id школы не найден..",
                "code" => 404,
            ],404);
        }
        if($user->id == $school->user_id) {
            $school->update([
                'country_id' => $request->country_id ?? 1,
                'city_id' => $request->city_id ?? null,
                'title_specialized_school' => $request->title_specialized_school ?? null,
                'title_specialized_school_id' => $request->title_specialized_school_id ?? null,
                'year_start' => $request->year_start ?? null,
                'year_ended' => $request->year_ended ?? null,
                'date_of_issue' => $request->date_of_issue ?? null,
                'group_school' => $request->group_school ?? null, 
                'speciality_id' => $request->speciality_id ?? null,
                'specialized_school_serial_number' => $request->specialized_school_serial_number ?? null,
                'direction_id' => $request->direction_id ?? null
            
            ]);
            $school->save();
            return $school;
        } else {
            return response()->json([
                "message" => "Подумайте еще раз..",
                "code" => 404,
            ],404);
        }    
    }

    public function getSpeciality(Request $request)
    {
        return SpecializedSecondarySpeciality::all();
    }

    public function getDirections(Request $request)
    {
        return SpecializedSecondaryDirection::all();
    }


    public function getSpecialitysInDirection(Request $request, $direction_id)
    {   

        return \DB::table('specialized_secondary_specialities')->where('direction_id', $direction_id)->get();
    }

    public function newSpeciality(Request $request)
    {
        $speciality = new SpecializedSecondarySpeciality;
        $speciality->title = $request->title;
        $speciality->direction_id = $request->direction_id;
        $speciality->moderated = 0;
        $speciality->save();
        return $speciality;
    }

    public function newDirection(Request $request)
    {
        $direction = new SpecializedSecondaryDirection;
        $direction->title = $request->title;
        $direction->moderated = 0;
        $direction->save();
        return $direction;

    }
}
