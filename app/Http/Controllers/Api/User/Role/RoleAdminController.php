<?php

namespace App\Http\Controllers\Api\User\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


use App\Models\User;
use App\Models\Admin\AdminRole;
use App\Models\Admin\AdminPermission;

use App\Services\RoleAndPermissionService;


class RoleAdminController extends Controller
{
    public function __construct(RoleAndPermissionService $rp)
    {
        $this->rp = $rp;
    }


    public function createRole(Request $request)
    {
        $user = User::find(Auth::id());

        $newRole = new AdminRole();
        $newRole->role_title = $request->role_title;
        $slug_permission = Str::of($request->role_title)->slug('_');
        $newRole->slug = $slug_permission;
        $newRole->save();


        $permissions = collect($request->all());
        unset($permissions['role_title']); 

        $perms_ids = $this->rp->getIdForPermissionAdminModels($permissions);

        $newRole->permissions()->attach($perms_ids);

        return collect($newRole);       

    }


    public function editRole(Request $request)
    {   

        $user = User::find(Auth::id());
        $editRole = AdminRole::find($request->admin_role_id);

        $editRole->role_title = $request->role_title;
        $editRole->slug = Str::of($request->role_title)->slug('_');
        $editRole->save();


        $permissions = collect($request->all());

        unset($permissions['role_title']);
        unset($permissions['admin_role_id']);


        $perms_ids = $this->rp->getIdForPermissionAdminModels($permissions);


        $editRole->permissions()->sync($perms_ids);

        return collect($editRole);


    }


    public function listRolesAvailable(Request $request)
    {
        $user = User::find(Auth::id());
        $rolesAdmin = AdminRole::all();
        return collect($rolesAdmin);

    }

    public function deleteRole(Request $request)
    {
        $user = User::find(Auth::id());
        $delRole = AdminRole::find($request->admin_role_id);
        $delRole->permissions()->detach();
        $delRole->delete();


        return response()->json([
            'message' => 'Роль успешно удалена..'
        ], 301);

        
    }







}
