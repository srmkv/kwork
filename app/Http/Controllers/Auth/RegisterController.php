<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;

//http
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

//facades
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

//requests
use App\Http\Requests\FullRegistrationRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifiedPinRequest;
use App\Http\Requests\PinRequest;

//models
use App\Models\BannedIpAddress;
use App\Models\Pin;
use App\Models\User;
use App\Models\Profiles\ProfileIndividual;

//services
use App\Services\Auth\Pin\CreateVerifyPinService;
use App\Services\Auth\CheckExistUserPhoneService;
use App\Services\Auth\RegisterService;


use Illuminate\Support\Facades\Auth;

//custom
use Carbon\Carbon;

//resources
use App\Http\Resources\PinResource;


class RegisterController extends Controller
{
    
    public function __construct(CreateVerifyPinService $sendpin)
    {
        $this->verifypin = $sendpin;
    }

    public function send(PinRequest $request)
    {   
        // повторная отправка
        if(isset($request->pin_id)){
           return $this->verifypin->checkCurrentPin($request->pin_id);
        }

        //если юзер восстанавливает доступ
        if ( $request->recovery_phone != null)  {
            $user_db = \DB::table('users')->where('phone',  $request->recovery_phone)->get();
            // dd($user_db);
            $user = User::find($user_db[0]->id);
            $user->verified = 5;
            $user->save();
            return $this->verifypin->checkCurrentUser($request->recovery_phone, $user);
        }
        // новая рега
        if (isset($request->phone)) {
            return $this->verifypin->checkCurrentUser($request->phone);
        }

        return 305;
    }





    public function verifyRecoverPhone(PinRequest $request)
    {
        $pin = Pin::find($request->pin_id);
        $user = User::find($pin->user_id);
        $input_pin = $request->pin;
        
        return $this->verifypin->recoveryVerify($pin, $user, $input_pin);

    }


    public function verifiedPin(VerifiedPinRequest $request)
    {   


        $pin = Pin::where('id', $request->pin_id)->first();

        if(!isset($pin) || $pin == null || !isset($request->user_id)){
            return response()->json([
                'message' => 'Пин кода не существует.. или данные не корректны..',
                'code' => 403
            ],403);
        }

        // dd($pin);
        $user = User::find($request->user_id);

        //если 3 раза подряд ввели не верный pin-код
        //убрать мусор этот
        if ($pin->count_attempts >= 3) {
            $timeout = match ($pin->count_timeout) {
                1 => config('timeout.register_timeout.first'),
                2 => config('timeout.register_timeout.second'),
                3 => config('timeout.register_timeout.third'),
                4 => config('timeout.register_timeout.banned'),
            };

            //

            $pin->count_timeout++;
            $pin->count_attempts = 0;
            $pin->save();

            if ($timeout == 'banned') {
                $banned = new BannedIpAddress();
                $userIpServices = new UserIpServices();

                $banned->ip = $userIpServices->getUserIp();
                $banned->save();
            }

            return response()->json([
                'message' => 'попытки ввода завершились',
                'timeout' => $timeout,
                'code'    => 403
            ], 403);



        }

        //если pin-код не верный
        if ($request->pin != $pin->pin) {
            $pin->count_attempts++;
            $pin->save();

            return response()->json([
                'message' => 'неверный pin-код',
                'code' => 403,
            ], 403);
        }

        $pin->save();
        //создаем профиль физ. лица
        $profile = new ProfileIndividual;
        $profile->user_id = $user->id;
        $profile->phone = $user->phone;
        $token = $user->createToken('access_token')->plainTextToken;
        $profile->save();

        \DB::table('dev_tokens')->insert([

            'user_id' => $user->id,
            'type_user' => 'individual/verified',
            'user_token' => $token
        ]); 

        return response()->json([
            'code' => 201,
            'user_id' => $pin->user_id,
            'access_token' => $token,
        ], 200);
    }


    //тут ставим первичный пароль после пина
    public function setPassword(FullRegistrationRequest $request)
    {   
        $user = User::findOrFail($request->user_id);
        $user->password = Hash::make($request->password);
        $user->verified = 1;
        $user->save();
        return $user;
    }


    public function checkPhone(RegisterRequest $request)
    {   
        $tempUser = User::where('phone',$request->phone)->first();
        if($tempUser) {

            if($tempUser->verified == 6){

                // return $this->verifypin->checkCurrentUser($request->phone, $tempUser);

                return response()->json([
                    'message' => 'Первичная авторизация сотрудника',
                    'code' => 206,
                    'employee_phone' => $request->phone
                ], 201);


            
            } else {
                return response()->json([
                    'message' => 'Пользователь найден',
                    'code' => 204,
                    'login_phone' => $request->phone
                ], 201);
            }

        } else {
           return response()->json([
               'message' => 'Пользователь не найден',
               'code' => 404,
               'redirect_to' => '/registration'
           ], 404);
        }




    }





}

