<?php

namespace App\Http\Controllers\Auth;


use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;


use App\Http\Requests\ChangePasswordRequest;


class PasswordController extends Controller
{
    
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {   

        $user = User::find(Auth::id());

        $current_pass = \DB::table('users')->where('id',$user->id)->get('password')[0]->password;

        $new_password = Hash::make($request->new_password);

        // Hash::check(normal_password,hashed_password);

        // dd($current_pass, $new_password, $)
        if ( Hash::check($request->current_password, $current_pass) ) {


            \DB::table('users')
                        ->where('id',$user->id)
                        ->update(['password' => $new_password ]);


            return response()->json([
                       'code'  => 200,
                       'message' => "Вы успешно сменили пароль",

            ],201);

        }


        // $request->user()->fill([
        //             'password' => Hash::make($request->new_passwword)
        //         ])->save();




        return response()->json([
                   'code'  => 419,
                   'message' => "Старый пароль не верный.. попробуйте еще раз..",
        ],419);
// password







    }


}
