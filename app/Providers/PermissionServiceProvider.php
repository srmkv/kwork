<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\Admin\AdminPermission;

class PermissionServiceProvider extends ServiceProvider
{

    public function register()
    {
        //
    }

    public function boot()
    {   

        // некоторые супер роли возможно будут проверяться только(!) в мидлварах, 
        // в контролерах/сервисах юзаем hasRoleAdmin && hasRoleEmployee соответственно
         try {

            // dd( AdminPermission::get());
            AdminPermission::get()->map(function ($permission) {
                // dd($permission);
                Gate::define($permission->slug, function ($user) use ($permission) {
                    // dd($permission);
                    return $user->hasPermissionToAdmin($permission);
                });
            });
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}
