<?php

namespace App\Http\Controllers\Password;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;;

class ResetPasswordController extends Controller
{
    //Тоже самое что и Forgot Password Controller?
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
        }

        $user = User::where('email',$request->email);
        $user->update([
            'password'=>Hash::make($request->password)
        ]);

        $token = $user->first()->createToken('myapptoken')->plainTextToken;

        return new JsonResponse(
            [
                'success' => true,
                'message' => "Ваш пароль был сброшен",
                'token'=>$token
            ],
            200
        );
    }
}
