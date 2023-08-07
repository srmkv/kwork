<?php

namespace App\Http\Middleware;
use App\Models\User;
use App\Models\Admin\AdminRole;

use Illuminate\Support\Facades\Cache;
use \Laravel\Sanctum\PersonalAccessToken;


use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class RoleMiddleware
{


    public function handle($request, Closure $next, $permission = null)
        {   
            $adminRolesList = AdminRole::all()->toArray(); // все существующие роли админов

            $token = $request->bearerToken();
            //todo #303 добавить кешированеи токена, чтобы не искать каждый раз его
            $token = PersonalAccessToken::findToken($token);
            $user = $token->tokenable;
            
            if($user) {

                // 0.аутентифицируем
                auth()->login($user); 


                // 1.проверим что у юзера есть хотя бы 1 админская роль
                if(count($user->adminRoles) > 0 ) {
                    // 2. Заглушка на права
                    // dd(Gate::allows('edit_listener')); // 1 если есть право, 0 если нет
                    return $next($request);

                } else {

                    return response()->json([
                        'message' => 'У вас недоостаточно прав на это действие..',
                        'code' => 422
                    ], 422);

                }

            }

        }

    
}
