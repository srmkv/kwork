<?php
namespace App\Traits;


use App\Models\Employee\EmployeePermission;
use App\Models\Employee\EmployeeRole;

use App\Models\Admin\AdminPermission;
use App\Models\Admin\AdminRole;

trait HasRolesAndPermissions
{
    // роли админов 
    public function adminRoles()
    {   //user_id admin_role_id
        return $this->belongsToMany(AdminRole::class,'user_admin_roles'); 
    }

    // роли сотрудников
    public function employeeRoles()
    {
        return $this->belongsToMany(EmployeeRole::class,'users_employee_roles');
        // return $this->belongsTo(EmployeeRole::class,'users_employee_roles');
    }



    // список конкретных прав конкретных админов
    public function adminPermissions()
    {
        return $this->belongsToMany(AdminPermission::class,'admin_permissions', 'user_id', 'admin_permission_id');
    }



    // список прав сотрудников соотвественно..
    public function employeePermissions()
    {
        return $this->belongsToMany(EmployeePermission::class,'employee_permissions');
    }


    // проверим роль админа
    public function hasRoleAdmin($roles) {
        // dd($roles);
        foreach ($roles as $role) {
            if ($this->adminRoles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }


    // проверим роли сотрудника
    public function hasRoleEmployee(... $roles ) {
        foreach ($roles as $role) {
            if ($this->employeeRoles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }


    //права доступа админа
    public function hasPermissionAdmin($permission)
    {   
       
        // dd($this->adminPermissions); //App\Models\Admin\AdminPermission

        return (bool) $this->adminPermissions->where('slug', $permission->slug)->count();
    }



    //привязана ли админская роль с правами к юзеру? (финальный gate)
    public function hasPermissionToAdmin($permission)
    {  
        return $this->hasPermissionAdmin($permission);
    }



    // проверка конкретного права у пользователя через его роль
    public function hasPermissionAdminThroughRole($permission)
    {
        foreach ($permission->roles as $role){
            if($this->roles->contains($role)) {
                return true;
            }
        }
        return false;
    }



    
    public function getAllPermissions(array $permissions)
    {
        return Permission::whereIn('slug',$permissions)->get();
    }

    public function getAllPermissionsEmployee(array $permissions)
    {
        return EmployeePermission::whereIn('slug',$permissions)->get();
    }




    //прикрепим права к юзеру 
    public function givePermissionsTo(... $permissions)
    {
        $permissions = $this->getAllPermissions($permissions);
        if($permissions === null) {
            return $this;
        }
        $this->permissions()->saveMany($permissions);
        return $this;
    }


    //прикрепим права к сотруднику
    public function givePermissionsToEmployee(... $permissions)
    {
        $permissions = $this->getAllPermissionsEmployee($permissions);
        if($permissions === null) {
            return $this;
        }
        $this->employeePermissions()->saveMany($permissions);
        return $this;
    }



    // заберем права 
    public function deletePermissions(... $permissions )
    {
        $permissions = $this->getAllPermissions($permissions);
        $this->permissions()->detach($permissions);
        return $this;
    }

    // переназначить заново права
    public function refreshPermissions(... $permissions )
    {
        $this->permissions()->detach();
        return $this->givePermissionsTo($permissions);
    }




}

