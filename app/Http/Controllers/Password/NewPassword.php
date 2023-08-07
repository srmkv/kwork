<?php

namespace App\Http\Controllers\Password;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;


class NewPassword extends Controller
{
    public function resetPassword(Request $request): JsonResponse
    {   

        $user = User::find(Auth::id());

        $user->update([
            'password'=>Hash::make($request->new_password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;


        return response()->json([
                   'code'  => 200
                   'message' => "Ваш пароль был сброшен",
                   'token'=>$token
        ]);
    }

}
