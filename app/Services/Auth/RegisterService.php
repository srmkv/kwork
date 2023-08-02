<?php
namespace App\Services\Auth;
use Illuminate\Support\Collection;


//models
use App\Models\Pin;
use App\Models\User;

//custom
use Carbon\Carbon;


class RegisterService {

    public function existPhone($phone) 
    {
        $user = User::where('phone', $phone)->first();
        if($user) {
            return response()->json([
                'message' => 'Пользователь найден',
                'code' => 200,
                'login_phone' => $phone
            ], 404);
        } else {   
           return response()->json([
               'message' => 'Пользователь не найден',
               'code' => 404,
               'redirect_to' => '/registration'
           ], 404);
        }
    }

}