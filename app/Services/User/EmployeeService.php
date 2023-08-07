<?php
namespace App\Services\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

use App\Models\Profiles\ProfileBuiseness;
use App\Models\Profiles\ProfileIndividual;
use App\Models\Profiles\ProfileSelfEmployed;

use App\Models\Employee;
use App\Models\Employee\EmployeeRole;
use App\Models\Employee\EmployeePermission;
use App\Http\Resources\Dashboard\MemberCompaniesResource;



class EmployeeService {


    public function getCompanies($profile_id)
    {
        
        $bis = \DB::table('profilebles')->where('profile_id', $profile_id)->where('profiles_individuals_type', 'business')->get();
        $ip = \DB::table('profilebles')->where('profile_id', $profile_id)->where('profiles_individuals_type', 'ip')->get();
        
        $businessCompanies =  MemberCompaniesResource::collection($bis->merge($ip));
        if($businessCompanies->isEmpty()) {
            return response()->json([
                'message' => 'Вы не являетесь сотрудником компании или у вас нет приглашений',
                'code' => 404
            ], 201);

        } else {
            return $businessCompanies;
        }


        
    }


    public static function fioProfile($profile_id)
    {
        $userWho = ProfileIndividual::find($profile_id);
        $fio = $userWho->name  . " " .  $userWho->middle_name . " " .  $userWho->lastname ;
        return $fio;
    }


    public static function currentRoleEmployeeIntoCompany($profile_id, $company_id, $type)
    {
        return \DB::table('profilebles')->where('profile_id', $profile_id)
            ->where('profileble_id', $company_id)
            ->where('profiles_individuals_type', $type)->first()->role_id;
    }


    public static function currentPositionEmployeeIntoCompany($profile_id, $company_id, $type)
    {
        return \DB::table('profilebles')->where('profile_id', $profile_id)
            ->where('profileble_id', $company_id)
            ->where('profiles_individuals_type', $type)->first()->job_position_employee;
    }




}