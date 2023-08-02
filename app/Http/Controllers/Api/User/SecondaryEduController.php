<?php
namespace App\Http\Controllers\Api\User;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SecondaryEdu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Company;
use App\Services\UserDocuments\StatusDocService;
use App\Services\UserDocuments\SchoolService;

class SecondaryEduController extends Controller
{   
    public function __construct(SchoolService $schoolAction)
    {
        $this->schoolAction = $schoolAction;
    }

    public function getSchools(Request $request)
    {
        $user = Company::currentUserForAction($request);
        return $this->schoolAction->getSchools($user->id);

    }

    public function getSchoolById(Request $request, $school_id)
    {
        return SecondaryEdu::find($school_id);
    }

    public function createSchool(Request $request)
    {
        $user = Company::currentUserForAction($request);
        $school = new SecondaryEdu;
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
        $school = SecondaryEdu::find($request->school_id);
        return $this->schoolAction->delete($school, $user->id);
    }


    public function editSchool(Request $request)
    {   
        $user = Company::currentUserForAction($request);
        $user_id = $user->id;
        $school_id = $request->school_id;
        $school = SecondaryEdu::find($school_id);
        if (!isset($school)){
            return response()->json([
                "message" => "Школа не обнаружена..",
                "code" => 404,
            ],404);
        }

        if($user->id == $school->user_id) {

            $school->update([
                'country_id' => $request->country_id ?? 1,
                'city_id' => $request->city_id ?? null,
                'title_school' => $request->title_school ?? null,
                'title_school_id' => $request->title_school_id ?? null,
                'year_start' => $request->year_start ?? null,
                'year_ended' => $request->year_ended ?? null,
                'date_of_issue' => $request->date_of_issue ?? null,
                'school_class' => $request->school_class ?? null, 
                'speciality_id' => $request->speciality_id ?? null,
                'school_serial_number' => $request->school_serial_number ?? null
            
            ]);
            $school->save();
            return response()->json([
                "message" => "Зафкиксировано..",
                "code" => 201,
            ],201);
        } else {
            return response()->json([
                "message" => "Вы не можете редактироавть эту школу..",
                "code" => 404,
            ],404);
        }
        
    }
}
