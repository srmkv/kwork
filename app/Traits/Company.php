<?php
namespace App\Traits;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Profiles\ProfileBuiseness;
use App\Models\Profiles\ProfileIndividual;
use App\Models\Profiles\ProfileSelfEmployed;

use App\Services\User\BusinessService;
use App\Traits\Profile;
trait Company
{   
    public static function currentUserForAction($request)
    {
        if(isset($request->type_company) && isset($request->company_id)){
            if(BusinessService::isAnEmployee($request->type_company, $request->company_id, $request->profile_id)) {
                return  User::find(Profile::getUserId($request->profile_id));
            } else {
                return response()->json([
                    "message" => "Вы не можете этого сделать..",
                ], 201);
            }
        } else{
            return User::find(Auth::id());
        }
    }

    public static function whatCompany($request)
    {
        $user = User::find(Auth::id());
        switch ($request->type) {
            case 'business':
                $company = ProfileBuiseness::find($request->company_id);
                break;
            case 'ip' :
                $company = ProfileSelfEmployed::find($request->company_id);
                break;
            default:
                return response()->json([
                    'message' => 'Не верно указан тип компании..',
                    'code' => 403
                ], 403);
                break;
        }
        if (!is_null($company) && Company::belongsUser($company, $user)) {
            return $company;
        } else {
            return response()->json([
                'message' => 'У вас нет доступа для управления этой компанией, либо компания не сушествует..',
                'code' => 403
            ], 403);
        }

    }

    public static function belongsUser($company, $user)
    {
        return $user->id == $company->user_id ? true : false;
    }

}
