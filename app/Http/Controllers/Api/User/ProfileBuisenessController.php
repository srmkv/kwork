<?php
namespace App\Http\Controllers\Api\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Employee\EmployeePermission;
use App\Models\Profiles\ProfileBuiseness;
use App\Models\Profiles\ProfileIndividual;
use App\Models\Profiles\ProfileSelfEmployed;
use App\Models\Employee;
use App\Http\Resources\User\EmploeeWithinCompanyResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Services\User\BusinessService;
use App\Services\User\EmployeeService;
use App\Services\UserDocuments\InnService;


class ProfileBuisenessController extends Controller
{   
    public function __construct(BusinessService $business, InnService $inn)
    {
        $this->business = $business;
        $this->inn = $inn;
    }

    public function editProfile(Request $request)
    {   
        $user_id = Auth::id();
        $user = User::find($user_id);
        $form = $request->all();
        $company_id = $request->business_profile_id;
        if(!empty(ProfileBuiseness::find($company_id))) {
            $company = ProfileBuiseness::find($company_id);
            if($company->user_id == $user_id) {
                $company->update([
                    'country_id' => $request->country_id,
                    'activity_type' => $request->activity_type,
                    'inn' => $request->inn,
                    'kpp' => $request->kpp,
                    'ogrn' => $request->ogrn,
                    'title_bank' => $request->title_bank,
                    'bank_account' => $request->bank_account,
                    'correspondent_account' => $request->correspondent_account,
                    'bik' => $request->bik,
                    'management_position' => $request->management_position,
                    'act_basis' => $request->act_basis,
                    'phone_company' => $request->phone_company,
                    'mail_company' => $request->mail_company,
                    'tax_type_id' => $request->tax_type_id,
                    'full_title' => $request->full_title,
                    'short_title' => $request->short_title,
                    'buiseness_address' => $request->buiseness_address,
                    'fact_address' => $request->fact_address,
                    'mailing_address' => $request->mailing_address,
                    'index' => $request->index,
                ]);
                return $company;
            }
        } else {
            $company = ProfileBuiseness::create([
                'country_id' => $request->country_id,
                'activity_type' => $request->activity_type,
                'inn' => $request->inn,
                'kpp' => $request->kpp,
                'ogrn' => $request->ogrn,
                'title_bank' => $request->title_bank,
                'bank_account' => $request->bank_account,
                'correspondent_account' => $request->correspondent_account,
                'bik' => $request->bik,
                'management_position' => $request->management_position,
                'act_basis' => $request->act_basis,
                'phone_company' => $request->phone_company,
                'mail_company' => $request->mail_company,
                'tax_type_id' => $request->tax_type_id,
                'full_title' => $request->full_title,
                'short_title' => $request->short_title,
                'buiseness_address' => $request->buiseness_address,
                'fact_address' => $request->fact_address,
                'mailing_address' => $request->mailing_address,
                'index' => $request->index,
                'user_id' => $user_id 
            ]);

            $this->business->attachDefaultRole($company);
            return $company;
        }
        return 799;
    }

    public function createEmployee(Request $request)
    {   $user = User::find(Auth::id());
        return $this->business->createEmployeeIntoCompany(
                $request->company_id,
                $request->type,
                $request->phone,
                $request->role_id ?? null,
                $user
        );
    }
    
    // меняем сотрудника как компания через "инфомация"
    public function editEmployee(Request $request, $profile_id)
    {
        return $this->business->editEmployeeByCompany($request->all(), $profile_id);
    }

    // показать одного сотрудника
    public function showEmployee(Request $request, $profile_id)
    {   
        $type = $request->type;
        $company_id = $request->company_id;
        $profile = ProfileIndividual::find($profile_id);
        $request->role_id = EmployeeService::currentRoleEmployeeIntoCompany($profile_id, $company_id, $type);
        $request->job_position = EmployeeService::currentPositionEmployeeIntoCompany($profile_id, $company_id, $type);
        return EmploeeWithinCompanyResource::make($profile);
    }

    // список сотрудников
    public function getEmployees(Request $request, $type, $company_id)
    {   
        return $this->business->allEmployeesIntoCompany($type, $company_id);
    }

    public function taxType(Request $request)
    {
        return \DB::table('tax_type')->get();
    }

    public function getProfile(Request $request, $id)
    {
        $user = User::find(Auth::id());
        if($user->ProfileBuiseness && ProfileBuiseness::find($id)->user_id == $user->id ) {
            return collect(ProfileBuiseness::find($id));
        } else {
            return response()->json([
                "message" => "Попробуйте сначала создать профиль..",
                "code" => 403,
            ],403);
        }
    }

    public function loadLogo(Request $request)
    {   

        $user = User::find(Auth::id());
        $id = $request->profile_business_id;
        $profile = ProfileBuiseness::find($id);
        $profile->addMediaFromRequest('logo')->withCustomProperties([
            'profile_business_id' => intval($id),
            'user_id' => intval($user->id),
        ])->toMediaCollection('profile_business_logo');
        $media_id = $profile->getMedia('profile_business_logo')->last()->id;
        $profile->media_logo_id = $media_id;
        $profile->save();
        return response()->json([
            "message" => "Лого юр. лица успешно загружено..",
            "code" => 201,
        ],201);
    }

    public function showLogo(Request $request)
    {   
        $user = User::find(Auth::id());
        $id = $request->profile_business_id;
        $profile = ProfileBuiseness::find($id);
        if($profile->media_logo_id != null ) {
            $logo = Media::find($profile->media_logo_id);
            $mime_logo = file_get_contents($logo->getPath());
            return response($mime_logo)->withHeaders([
                'Content-Type' => mime_content_type($logo->getPath())
            ]);
        }
    }

    public function loadAct(Request $request)
    {   
        $user = User::find(Auth::id());
        $id = $request->profile_business_id;
        $profile = ProfileBuiseness::find($id);
        $profile->addMediaFromRequest('act')->withCustomProperties([
            'profile_business_id' => intval($id),
            'user_id' => intval($user->id),
        ])->toMediaCollection('profile_business_act');
        $media_id = $profile->getMedia('profile_business_act')->last()->id;
        $profile->media_act_id = $media_id;
        $profile->save();
        return response()->json([
            "message" => "Приказ загружен..",
            "code" => 201,
        ],201);
    }

    public function showAct(Request $request)
    {   
        $user = User::find(Auth::id());
        $id = $request->profile_business_id;
        $profile = ProfileBuiseness::find($id);
        $act = Media::find($profile->media_act_id);
        $mime_logo = file_get_contents($act->getPath());
        return response($mime_logo)->withHeaders([
            'Content-Type' => mime_content_type($act->getPath())
        ]);
    }

    public function getDataByInn(Request $request)
    {
        return $this->inn->getDataByInn($request->inn, $request->type);
    }
}
