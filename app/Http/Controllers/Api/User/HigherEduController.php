<?php
namespace App\Http\Controllers\Api\User;

use App\Models\User;
use App\Models\HigherEdu;
use App\Models\HigherEdu\HigherEduLevel;
use App\Models\HigherEdu\HigherEduSpeciality;
use App\Models\HigherEdu\HigherEduDirection;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Company;
use App\Services\UserDocuments\StatusDocService;
use App\Services\UserDocuments\HigherEduService;

class HigherEduController extends Controller
{   
    public function __construct(HigherEduService $higher)
    {    
        $this->higher = $higher;      
    }

    public function listLevel(Request $request)
    {
        return collect(HigherEduLevel::all());
    }

    public function diploms(Request $request)
    {   
        $user = Company::currentUserForAction($request);
        return $this->higher->getDiploms($user->id);
    }

    public function getDiplomById(Request $request, $diplom_id)
    {
        return HigherEdu::find($diplom_id);
    }

    public function createDiplom(Request $request)
    {
        return $this->higher->new($request);
    } 


    public function deleteDiplom(Request $request)
    {
        $user = Company::currentUserForAction($request);
        $diplom = HigherEdu::find($request->diplom_id);
        return $this->higher->deleteDiplom($diplom, $user->id);
    }

    public function editDiplom(Request $request)
    {
        return $this->higher->update($request);
    }

    public function getSpeciality(Request $request)
    {
        return HigherEduSpeciality::all();
    }

    public function getSpecialitysInDirection(Request $request, $direction_id)
    {   

        return \DB::table('higher_edu_specialities')->where('direction_id', $direction_id)->get();
    }


    public function newSpeciality(Request $request)
    {
        $speciality = new HigherEduSpeciality;
        $speciality->title = $request->title;
        $speciality->direction_id = $request->direction_id;
        $speciality->moderated = 0;
        $speciality->save();
        return $speciality;
    }


    public function newDirection(Request $request)
    {
        $direction = new HigherEduDirection;
        $direction->title = $request->title;
        $direction->moderated = 0;
        $direction->save();
        return $direction;
    }
    
    public function getDirections(Request $request)
    {
        return HigherEduDirection::all();
    }



}

