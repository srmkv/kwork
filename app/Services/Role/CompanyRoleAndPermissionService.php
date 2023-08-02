<?php
namespace App\Services\Role;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\Profiles\ProfileIndividual;
use Illuminate\Support\Facades\Auth;

use App\Models\Employee\EmployeeRole;

//custom
use Carbon\Carbon;

class CompanyRoleAndPermissionService {

    public function __construct()
    {
        $this->user = User::find(Auth::id());
    }

    public  function getIdForPermissionEmployeeModels($permissions)
    {   
        $permissions_ids = [];
        foreach ($permissions as $slug => $permission) {            
            $check_id = \DB::table('permissions_employee')->where('slug', $slug)->first()->id;
            if ($permission == 1 && isset($check_id)) {
                array_push($permissions_ids,$check_id);
            }
        }
        return $permissions_ids;
    }



    public function getIdForPermissionAdminModels($permissions)
    {
        $permissions_ids = [];
        foreach ($permissions as $slug => $permission) {
            $check_id = \DB::table('permissions_admin')->where('slug', $slug)->first()->id;
            if ($permission == 1 && isset($check_id)) {
                array_push($permissions_ids,$check_id);
            }
        }
        return $permissions_ids;
    }

    public function deleteRole($role, $company)
    {
        if($role->default_role) {
            return response()->json([
                'message' => 'Вы не можете удалить дефолтную роль..',
                'code' => 403
            ], 403);
        } else {
            $role->permissions()->detach();
            $this->beforeDeleteRole($role, $company);
            $role->delete();

            return response()->json([
                'message' => 'Эта роль была успешна удалена, все сотрудникам компании с этой ролью были сброшены до дефолтной роли..',
                'code' => 200
            ],200);
        }
    }

    public function beforeDeleteRole($role, $company)
    {
        // удалим эту роль у всех юзеров у которых она была
        // обнулим права, и переназначим на дефолтную роль "участник"
        $typeCompany = $this->typeCompany($company);

        // получи дефолтную роль "участник компании"
        $availableDefaultRole =  EmployeeRole::where([
            ['company_id',  $company->id],
            ['type', $typeCompany],
            ['default_role', 1]
        ])->get()->last();

        // получим всех юзеров, которым надо скинуть роль
        // взамен удаляемой на дефолтную
        \DB::table('profilebles')->where([
            'profileble_id' => $company->id,
            'profiles_individuals_type' => $typeCompany,
            'role_id' => $role->id
        ])->update([
            'role_id' => $availableDefaultRole->id
        ]);
    }

    public function typeCompany($company)
    {
        switch (get_class($company)) {
            case 'App\Models\Profiles\ProfileBuiseness':
                return 'business';
                break;
            case 'App\Models\Profiles\ProfileSelfEmployed':
                return 'ip';
                break;
            default:
                return response()->json([
                    'message' => 'Ошибка при получении типа компании..',
                    'code' => 403
                ], 403);
                break;
        }
    }
}