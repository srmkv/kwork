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

use App\Models\Passport;
use App\Models\Replacename;

use App\Http\Resources\Dashboard\MemberCompaniesResource;
use App\Http\Resources\User\EmployeesListResource;
use App\Http\Resources\User\EmploeeWithinCompanyResource;

use App\Traits\Profile;

use App\Services\User\EmployeeService;


class BusinessService {

    const CREATOR_COMPANY = 'Создатель компании';
    const ATTENDEES_COMPANY = 'Участник компании';

    // ТИПЫ ДОКУМЕНТОВ КОТОРЫЕ РЕДАКТИРУЕТ КОМПАНИЯ
    const TYPE_PASSPORT = 1;


    public function __construct(EmployeeService  $employee)
    {   
        $this->employee = $employee;
    }


    public function attachDefaultRole(ProfileBuiseness $company)
    {
        $creator = EmployeeRole::create([
            'role_title' => $this::CREATOR_COMPANY,
            'company_id' => $company->id,
            'slug' => Str::of($this::CREATOR_COMPANY . "_" . Str::random(6))->slug('_'),
            'default_role' => 1
        ]);
        
        $attendees  = EmployeeRole::create([
            'role_title' => $this::ATTENDEES_COMPANY,
            'company_id' => $company->id,
            'slug' => Str::of($this::ATTENDEES_COMPANY . "_" .  Str::random(6))->slug('_'),
            'default_role' => 1
        ]);


        $user = User::find($company->user_id);
        $permissions = collect(EmployeePermission::all());
        $permissions_ids = [];

        // Для роли "создатель" назначим все права
        foreach ($permissions as $index => $permission) {
            $creator->permissions()->attach($permission->id);
            array_push($permissions_ids,$permission->id); 
        }

        // и также назначим их напрямую юзеру(владельцу)
        $user->employeePermissions()->sync($permissions_ids);

    }


    public function createEmployeeIntoCompany($id, $type_company, $phone, $role_id, $user)
    {   
        $employee = ProfileIndividual::where('phone',$phone)->first();
        $company = $type_company == 'business' ? ProfileBuiseness::find($id) : ProfileSelfEmployed::find($id);

        switch ($employee) {
            case true:
                return $this->checkOrCreateEmployee($company, $employee, $user, $role_id);
            break;
            case false:
                return $this->newEmployeeDB($phone, $company, $user, $role_id);
            break;
            default:
                return '';
            break;
        }
    }


    public function newEmployeeDB($phone, $company, $user, $role_id)
    {
        $employee = new ProfileIndividual;
        $employee->phone = $phone;
        $user_employee = new User;
        $user_employee->phone = $phone;
        $user_employee->name = 'new employee';
        $user_employee->verified = 6;

        if($user_employee->save()){
            $employee->user_id = $user_employee->id;
            $employee->save();   

            $company->employeesBusinsess()->attach($employee,[
                'profile_who_invited' => $user->individualProfile->id,
                'invitation_date'   => now(),
                'job_position_who'  => $company->management_position ?? 'ИП',
                'role_id' => $role_id
            ]);

            $profile = ProfileIndividual::find($employee->id);
            
            return [
                "profile_id" => $profile->id,
                "user_id" => $profile->user_id,
                "avatar"  => $profile->avatar,
                "lastname" => $profile->lastname,
                "name" => $profile->name,
                "middle_name" => $profile->middle_name,
                "phone" => $profile->phone,
                "email" => $profile->email,
                "date_birthday" => $profile->date_birthday,
                "avatarimage" => $profile?->avatarimage,

                "role_id"   => $role_id,
                "job_position" => 'Не назначена'
            ];
        } else {
            return response()->json([
                "message" => "Что то пошло не так..",
                "code" => 403,                
            ],403);

        }
    }

    public function editEmployeeByCompany($data, $profile_id)
    {
        $profile = ProfileIndividual::find($profile_id);
        
        $this->profile_id = $profile_id;
        $this->company_id = $data['company_id'];
        $this->type = $data['type_company'];

        $this->job_position = isset($data['job_position']) ? $data['job_position'] : null;
        $this->role_id = isset($data['role_id']) ? $data['role_id'] : null;

        $profile->lastname = $data['lastname'] ?? $profile->lastname;
        $profile->name = $data['name'] ?? $profile->name;
        $profile->date_birthday = $data['date_birthday'] ?? $profile->date_birthday;
        $profile->middle_name = $data['middle_name'] ?? $profile->middle_name;
        $profile->phone = $data['phone'] ?? $profile->phone;
        $profile->email = $data['email'] ?? $profile->email;

        $profile->save();

        if($this->role_id != null) {
            \DB::transaction(function()
            {
                \DB::table('profilebles')->where('profile_id', $this->profile_id)
                   ->where('profileble_id', $this->company_id)
                   ->where('profiles_individuals_type', $this->type)->update([
                       'role_id' => $this->role_id
                   ]);
            });
        }

        if($this->role_id != null) {
            \DB::transaction(function()
            {
                \DB::table('profilebles')->where('profile_id', $this->profile_id)
                    ->where('profileble_id', $this->company_id)
                    ->where('profiles_individuals_type', $this->type)->update([
                        'job_position_employee' => $this->job_position
                    ]);
            });
        };

        return EmploeeWithinCompanyResource::make($profile);
    }


    public function checkOrCreateEmployee($company, $employee, $user, $role_id)
    {
        // Проверим что добавляемый сотрудник еше не пренадлежит текущей компании
        if(\DB::table('profilebles')->where('profile_id', $employee->id)->where('profileble_id', $company->id)->get()->isNotEmpty()) {
            return response()->json([
                "message" => "Данный сотрудник уже состоит в вашей компании..",
                "code" => 403,
                "profile_id" => $employee->id,
            ],201);
        } else {
            
            // $employee->profile_who_invited
            
            $company->employeesBusinsess()->attach($employee,[

                'profile_who_invited' => $user->individualProfile->id,
                'invitation_date'   => now(),
                'job_position_who'  => $company->management_position ?? 'ИП',
                'role_id' => $role_id
            ]);

            // return response()->json([
            //     "message" => "Существующий сотрудник добавлен в вашу компанию..",
            //     "code" => 201,
            //     "profile_id" => $employee->id,
            // ],201);




            $profile = ProfileIndividual::find($employee->id);

            return [
                "profile_id" => $profile->id,
                "user_id" => $profile->user_id,
                "avatar"  => $profile->avatar,
                "lastname" => $profile->lastname,
                "name" => $profile->name,
                "middle_name" => $profile->middle_name,
                "phone" => $profile->phone,
                "email" => $profile->email,
                "date_birthday" => $profile->date_birthday,
                "avatarimage" => $profile?->avatarimage,

                "role_id"   => $role_id,
                "job_position" => 'Не назначена'
            ];


            // // $request->role_id = EmployeeService::currentRoleEmployeeIntoCompany($profile_id, $company_id, $type);
            // $request->role_id = $role_id;
            // // $request->job_position = EmployeeService::currentPositionEmployeeIntoCompany($profile_id, $company_id, $type);
            // $request->job_position = 'Не назначено';

            // return EmploeeWithinCompanyResource::make($profile);

        }
    }

    public function allEmployeesIntoCompany($type, $company_id)
    {   
        switch ($type) {
            case 'ip':
                $company = ProfileSelfEmployed::find($company_id);
                if($company == null ){
                    return response()->json([
                        'message' => 'Такой комппании не существует..',
                        'code' => 404
                    ], 404);
                }
                return EmployeesListResource::collection($company->employeesBusinsess);
            break;
            case 'business':
                $company = ProfileBuiseness::find($company_id);
                // dd($company);
                if($company == null ){
                    return response()->json([
                        'message' => 'Такой комппании не существует..',
                        'code' => 404
                    ], 404);
                }
                return EmployeesListResource::collection($company->employeesBusinsess);

            break;
            default:
                return response()->json([
                    "message" => "Неверный тип компании..",
                ],201);
            break;
        }
    }




    // ВСЕ НИЖЕ - СОЗДАНИЕ И РЕДАКТИРОВАНИЕ ДОКУМЕНТОВ ДЛЯ СОТРУДНИКОВ, ЕСЛИ МЫ - КОМПАНИЯ

    // может ли данная компания изменять документ сотруднику? (общий сервис для всех документов)
    public function canCompanyChangeDocument($type_company, $company_id, $type_document, $document_id, $profile_id)
    {
        $employee = ProfileIndividual::find($profile_id);
        switch ($type_document) {
            case $this::TYPE_PASSPORT :
                $documents = Passport::where('user_id', $employee->user_id)->get();
            break;

            default:
                return 'Такой тип документов не существует..';
            break;
        }

        if (
            // 1. Документ должен пренадлежать этому сотруднику
            $this->allEmployeesIntoCompany($type_company, $company_id)->contains('id', $profile_id) &&
            // 2. Сотрудник входит в эту компанию ( со статусом accept?)
            $documents->contains($document_id)
        ) { 
            // dd($this->allEmployeesIntoCompany($type_company, $company_id)->contains('id', $profile_id));
            return true;
        } else {
            return false;
        }
    }

    public function editPassportByCompany($request)
    {
        $passport = Passport::find($request->passport_id);
        $passport->country_id = $request->country_id ?? $passport->country_id;
        $passport->first_name = $request->first_name ?? $passport->first_name;
        $passport->last_name = $request->last_name ?? $passport->last_name;
        $passport->middle_name = $request->middle_name ?? $passport->middle_name ;
        $passport->date_of_birth = $request->date_of_birth ?? $passport->date_of_birth;
        $passport->serial_number = $request->serial_number ?? $passport->serial_number;
        $passport->date_issue = $request->date_issue ?? $passport->date_issue;
        $passport->subdivision_code = $request->subdivision_code ?? $passport->subdivision_code;

        $passport->save();

        return $passport;
    }

    public function addPassportByCompany($profile_id)
    {   
        $user = User::find(Profile::getUserId($profile_id));
        $passport = new Passport;
        $passport->user_id = $user->id;
        $passport->country_id = 1;

        $passport->save();
        $user->passports()->attach($passport);
        return response()->json([
            "message" => "Добавлен пустой паспорт..",
            "code" => 201,
            "passport" => $passport
        ],201);
    }

    public function addChangeNameByCompany($profile_id)
    {
        $user = User::find(Profile::getUserId($profile_id));

        $replaceName = new Replacename;
        $replaceName->user_id = $user->id;
        $replaceName->created_at = now();
        $replaceName->created_at = now();
        $replaceName->save();

        return response()->json([
            "message" => "Добавлен пустой документ на смену ФИО..",
            "code" => 201,
            "replaceName" => $replaceName
        ],201);
    }

    // является ли этот профиль участником компании
    public static function isAnEmployee($type, $company_id, $profile_id)
    {
        $bis = \DB::table('profilebles')->where('profile_id', $profile_id)->where('profiles_individuals_type', 'business')->get();
        $ip = \DB::table('profilebles')->where('profile_id', $profile_id)->where('profiles_individuals_type', 'ip')->get();
        $businessCompanies =  $bis->merge($ip);
        foreach ($businessCompanies as $company) {
            if($company->profileble_id == $company_id && $company->profiles_individuals_type == $type ) {
                return true;
            } 
        }
        return false;
    }


}