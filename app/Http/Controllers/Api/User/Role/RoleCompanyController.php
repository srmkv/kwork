<?php

namespace App\Http\Controllers\Api\User\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Employee\EmployeeRole;
use App\Models\Employee\EmployeePermission;
use App\Models\Admin\AdminRole;
use App\Models\Admin\AdminPermission;
use App\Models\Profiles\ProfileIndividual;
use App\Models\Profiles\ProfileSelfEmployed;
use App\Models\Employee;
use App\Models\Profiles\ProfileBuiseness;
use App\Models\Permission;
use App\Services\Role\CompanyRoleAndPermissionService;

use App\Traits\Company;

use App\Http\Resources\Role\GetRoleResource;


class RoleCompanyController extends Controller
{
    
    public function __construct(CompanyRoleAndPermissionService $rp)
    {
        $this->rp = $rp;
    }

    public function createRole(Request $request)
    {
        $user = User::find(Auth::id());
        $newRole = new EmployeeRole();
        //у ип также могут быть сотрудники..
        $type = $request->type;
        $newRole->role_title = $request->role_title;
        $newRole->type = $type;

        if(isset($request->company_id)){
            $newRole->company_id = $request->company_id;

        } else {
            //если добавляет роли собственник компании
            switch ($type) {
                case 'business':
                    $newRole->company_id = $user->ProfileBuiseness->id;
                    break;
                case 'ip':
                    $newRole->company_id = $user->ProfileSelfEmployed->id;
                    break;
                default:
                    return 988;    
                    break;
            }
        }

        $slug_permission = Str::of($request->role_title)->slug('_');
        $newRole->slug = $slug_permission;
        $newRole->save();  
        $permissions = collect($request->all());

        unset($permissions['company_id']);
        unset($permissions['role_title']);
        unset($permissions['type']);
        $permissions_slugs = collect([]);
        $permissions_ids = [];

        foreach ($permissions as $slug => $permission) {

            $check_id = \DB::table('permissions_employee')->where('slug', $slug)->first()->id;
            if ($permission == 1 && isset($check_id)) {
                $permissions_slugs->push($slug);
                array_push($permissions_ids,$check_id);
            }
        }

        $newRole->permissions()->attach($permissions_ids);
        return collect($newRole);        
    }

    public function checkRole(Request $request)
    {

        $user = User::find(Auth::id());
        return $user->employeePermissions;
    }

    public function allRolesIntoCompany(Request $request,  $type, $company_id)
    {
        $user = User::find(Auth::id());
            return EmployeeRole::where([
                ['company_id',  $company_id],
                ['type', $type]
            ])->get();
    }

    public function editRole(Request $request)
    { 
        $user = User::find(Auth::id());
        $editRole = EmployeeRole::find($request->company_role_id);
        $editRole->role_title = $request->role_title;
        $editRole->slug = Str::of($request->role_title)->slug('_');
        $editRole->save();
        $permissions = collect($request->all());
        unset($permissions['role_title']);
        unset($permissions['company_role_id']);
        $perms_ids = $this->rp->getIdForPermissionEmployeeModels($permissions);
        $editRole->permissions()->sync($perms_ids);
        return collect($editRole);
    }

    public function allPermissions(Request $request)
    {
        return EmployeePermission::all();
    }


    public function getRole(Request $request, $role_id)
    {
        $user = User::find(Auth::id());
        $role = EmployeeRole::find($role_id);
        return GetRoleResource::make($role);
    }


    public function deleteRole(Request $request)
    {
        $company = Company::whatCompany($request);
        $delRole = EmployeeRole::find($request->company_role_id);
        return $this->rp->deleteRole($delRole, $company);
    }

    public function attachRole(Request $request)
    {
        $user = User::find(Auth::id());
        $employee_id = $request->employee_id;
        $user_employee = User::find($employee_id);
        $role = EmployeeRole::find($request->company_role_id);
        $user_employee->employeeRoles()->sync($role);
        return response()->json([
            'message' => 'Роль успешно прикреплена..'
        ], 201);



    }
}
