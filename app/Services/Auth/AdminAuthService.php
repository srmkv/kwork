<?php
namespace App\Services\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class AdminAuthService {
    public function checkAttempt($login, $password) 
    {
        if(Auth::attempt(['name' => $login, 'password' => $password])) {

            $admin = Auth::user(); 
            $success['token'] =  $admin->createToken('auth_token')->plainTextToken;


            \DB::table('dev_tokens')->insert([
                'user_id' => auth()->user()->id,
                'type_user' => 'admin/login',
                'user_token' => $success['token']
            ]); 
            
            return response()->json([
                'message' => 'success sign-in admin',
                'code' => 200,
                'token' => $success['token'],
                'user_id' => auth()->user()->id,
                'mail' => $admin->email ?? null,
                'phone' => $admin->phone ?? null,
                'type_user' => auth()->user()->name,
            ], 200);

        } else {

            return response()->json([
                'message' => 'Неверный логин/пароль, либо у вас нет доступа'
            ], 401);
        }

    }

}