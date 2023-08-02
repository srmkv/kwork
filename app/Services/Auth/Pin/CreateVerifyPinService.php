<?php
namespace App\Services\Auth\Pin;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Illuminate\Http\JsonResponse;
use App\Models\Pin;
use App\Models\User;
use App\Models\Profiles\ProfileIndividual;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CreateVerifyPinService {
    const WAITING_TIME_RESEND_PIN = 120;

    // считаем сколько осталось секунд до след. отправки пина
    public  function checkSpamSms(string $date)
    {   
        $time_send_sms = Carbon::parse($date)->getTimestamp(); 
        $time_now = Carbon::now()->getTimestamp(); 
        $time_diff = $time_now - $time_send_sms;

        if ($time_diff <= self::WAITING_TIME_RESEND_PIN) { 
            // dd($time_diff);
            return false;


        } else {
            $remaining_time = self::WAITING_TIME_RESEND_PIN - $time_diff; 
            return $remaining_time; 
        }

    }

    public function checkCurrentUser($phone, $user_recovery = null)
    {   
        // когда восстанавливаем, юзер известен, а когда регаем нет
        if(!empty($user_recovery) ){
            if($user_recovery->verified == 5) {
                return $this->sendFirstPin($user_recovery->phone, $user_recovery->id);
            } 
        }

        $user = User::where('phone','=', $phone)->first();
        if($user == null) {
            // 1.user не успел зарегаться первый раз или вообще не регался
            $user = new User;
            $user->name = 'no auth';
            $user->phone = $phone;
            $user->save();
            return $this->sendFirstPin($phone, $user->id);
        } else {
            if ($user->verified == 0 ) {
                $pin = Pin::where('user_id', $user->id)->get()->last();
                // dd($pin);
                if( !is_null($pin) && $this->checkSpamSms($pin->created_at)){
                    return response()->json([
                        'message' => 'Для этого номера уже идет процесс регистрации..',
                        'redirect_to' => '/pin',
                        'time_left' => $this->checkSpamSms($pin->created_at),
                        'code'    => 202,
                    ], 202); 

                } else {
                    return response()->json([
                        'message' => 'Идет процесс регистрации, время пин кода истекло..',
                        'redirect_to' => '/pin',
                        'code' => 202
                    ], 202);
                }
            }
            if ($user->verified == 1) {
                return response()->json([
                    'message' => 'Этот номер уже был зарегистрирован, авторизуйтесь..',
                    'user_id' => $user->id,
                    'redirect_to' => '/login',
                    'phone' => $user->phone,
                    'code' => 203,
                ], 203);

            }
            if ($user->verified == 5) {

                $pin = Pin::where('user_id', $user->id)->get()->last();
                if( !is_null($pin) && $this->checkSpamSms($pin->created_at)){
                    return response()->json([
                        'message' => 'закончите восстановление пароля..',
                        'redirect_to' => '/recovery',
                        'phone' => $user->phone,
                        'user_id' => $user->id,
                        'time_left' => $this->checkSpamSms($pin->created_at),
                        'pin_id' => $pin->id,
                        'code'    => 200,
                        
                    ], 200); 

                } else {
                    return response()->json([
                        'message' => 'Идет процесс восстановления пароля.., время пин кода истекло..',
                        'redirect_to' => '/recovery',
                        'code' => 202,
                        'user_id' => $user->id,
                        'phone' => $user->phone,
                        
                    ], 202);
                }
            } 

            if ($user->verified == 6) {
                // Если оказалось что входит сотрудник, ему приходит пин
                return $this->sendFirstPin($phone, $user->id);
            }
        }
    }

    public function sendFirstPin($phone, $user_id)
    {   
        $infoPin = $this->curlFromSmsRu($phone);
        if($infoPin == false) {
            return response()->json([
                'message' => 'Звонок не может быть выполнен.. ошибки внешнего сервиса..',
                'code' => 403,
                'debug' => $infoPin
            ], 403);
        }
        if(!$infoPin) {
            return response()->json([
                 'message' => 'Запрос не выполнился.Не удалось установить связь с сервером',
                 'code' => 403
            ], 403);
        }
        
        if($infoPin->status == 'OK') {
            $new_pin = new Pin;
            $new_pin->phone_to = $phone;
            $new_pin->user_id = $user_id;
            $new_pin->created_at = now();
            $new_pin->updated_at = now();
            $new_pin->doublesend = 0;
            $new_pin->pin = $infoPin->code;
            $new_pin->save();
            return response()->json([      
                'message' => 'Звонок выполняется.. Введите 4 последних цифры номера..',
                'user_id' => $user_id,
                'pin_id' => $new_pin->id,
                'code'  => 201
            ], 200);
        } else {

            return response()->json([
                'debug_message' => collect(array($infoPin))
            ], 403);
        }
    }

    public function checkCurrentPin($pin_id)
    {
        $current_pin = Pin::find($pin_id);
        if($current_pin == null) {
            return response()->json([
                'message' => 'Попытки закончились , либо вы долго остутствовали..',
                'code' => 403,
            ], 403);
        }
        //когда запрашиваем пин , дополнительно проверяем что этот pin->id уже использоан
        if($current_pin->status == 403) {
                return response()->json([
                    'message' => 'Хм.. этот pin code более не действителен..',
                    'code' => 403,
                ], 403);
        } 

        $current_pin->doublesend = $current_pin->doublesend + 1;
        $current_pin->save();

        if ($current_pin->doublesend > 2) {
            return response()->json([
                'message' => 'Вы исчерпали все попытки..',
                'code' => 403,
            ], 403);
        } 
        if(!$this->checkSpamSms($current_pin->created_at)) {
            return response()->json([
                'message' => "Вы не можете получить код пока не пройдет:  120 секунд",
                'code' => 403,
            ], 403);
        } 
        if (($this->checkSpamSms($current_pin->created_at)) && ($current_pin->doublesend < 3)){  
            return $this->resendPin($current_pin);
        } 
    }

    public  function resendPin(Pin $pin) 
    {
        $phone_to = $pin->phone_to;
        $infoPin = $this->curlFromSmsRu($phone_to);
        if($infoPin == false ) {
            return response()->json([
                'message' => 'Звонок не может быть выполнен.. ошибки внешнего сервиса..',
                'code' => 403
            ], 403);
        }

        if($infoPin->status == 'OK') {
            //debug smsru
            \DB::table('dev_sms')->insert([
                'id_call' => $infoPin->call_id,
                'cost_call' => $infoPin->cost,
                'created_at' => now(),
                'balance_remainder' => $infoPin->balance
            ]);

            $new_pin = new Pin;
            $new_pin->phone_to = $phone_to;
            $new_pin->user_id = $pin->user_id;
            $new_pin->created_at = now();
            $new_pin->updated_at = now();
            $new_pin->doublesend = $pin->doublesend;
            $new_pin->pin = $infoPin->code;
            //предыдущему пину ставим 403, чтобы его не дергали еше раз
            $pin->status = 403;
            $pin->save();
            $new_pin->save();
            return response()->json([      
                'message' => 'Повторный звонок выполняется..будьте внимательнее..',
                'user_id' => $new_pin->user_id,
                'pin_id' => $new_pin->id
            ]);
        } 
    }

    //user phone
    public function changePhoneUser($phone)
    {   
        $profiles = ProfileIndividual::all();
        $user_id = Auth::id();

        if($profiles->contains('phone', $phone)) {
            return response()->json([      
                'message' => 'Вы не можете изменить свой номер телефона на этот..', 
                'code' => 422
            ],422);
        } else {
            return $this->sendChangePhonePin($phone, $user_id);
        }
    }


    public function sendChangePhonePin($phone_to, $user_id)
    {
        $pins = Pin::all();
        if($pins->contains('user_id', $user_id)) {
            return response()->json([
                'message' => 'Вы не можете запросить pin code, пока не закончится действие старого..',
                'code' => 403
            ], 403);
        }
        $infoPin = $this->curlFromSmsRu($phone_to);
        if($infoPin == false ) {
            return response()->json([
                'message' => 'Звонок не может быть выполнен.. ошибки внешнего сервиса..',
                'code' => 403
            ], 403);
        }

        if($infoPin->status == 'OK') {
            $new_pin = new Pin;
            $new_pin->phone_to = $phone_to;
            $new_pin->user_id = $user_id;
            $new_pin->created_at = now();
            $new_pin->updated_at = now();
            $new_pin->doublesend = 0;
            $new_pin->pin = $infoPin->code;
            $new_pin->save();
            return response()->json([      
                'message' => 'Звонок выполняется...',
                'user_id' => $new_pin->user_id,
                'pin_id' => $new_pin->id
            ]);
        }
    }


    public function recoveryVerify(Pin $pin, User $user, $pin_code)
    {   
        if ($pin_code != $pin->pin) {
            $pin->count_attempts++;
            $pin->save();
            return response()->json([
                'message' => 'неверный pin-код',
                'code' => 403,
            ], 403);
        }
        if ($pin_code == $pin->pin) { 
            $success['token']  = $user->createToken('auth_token')->plainTextToken;
            \DB::table('dev_tokens')->insert([
                'user_id' => $user->id,
                'type_user' => 'individual/login',
                'user_token' => $success['token']
            ]);


            $user->verified = 1;
            $user->save();

            return response()->json([
                'message' => 'Успешно',
                'code' => 201,
                'token' => $success['token'],
                'userid' => $user->id
            ], 200);
        }
    }


    public function checkVerifyPin($code, $pin_id, $phone)
    {
        $pin = Pin::where('id', $pin_id)->first();
        $user_id = Auth::id();
        $profile = ProfileIndividual::where('user_id', $user_id)->get()->first();
        $user = User::find(Auth::id());
        if ($pin->count_attempts >= 3) {
            $timeout = match ($pin->count_timeout) {
                1 => config('timeout.register_timeout.first'),
                2 => config('timeout.register_timeout.second'),
                3 => config('timeout.register_timeout.third'),
                4 => config('timeout.register_timeout.banned'),
            };
            $pin->count_timeout++;
            $pin->count_attempts = 0;
            $pin->save();
            // ????
            // if ($timeout == 'banned') {
            //     $banned = new BannedIpAddress();
            //     $userIpServices = new UserIpServices();
            //     $banned->ip = $userIpServices->getUserIp();
            //     $banned->save();
            // }
            return response()->json([
                'message' => 'попытки ввода завершились',
                'timeout' => $timeout,
                'code'    => 403
            ], 403);
        }
        if ($code != $pin->pin) {
            $pin->count_attempts++;
            $pin->save();
            return response()->json([
                'message' => 'неверный pin-код',
                'code' => 403,
            ], 403);
        }

        if ($code == $pin->pin) {
            $user->phone = $phone;
            $profile->phone = $phone;
            $user->save();
            $profile->save();
            return response()->json([
                'message' => 'Номер успешно изменён..',
                'code' => 201,
            ], 201);
        }
    }

    public  function curlFromSmsRu($phone_to)
    {   
        $ch = curl_init("https://sms.ru/code/call");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
            "phone" => $phone_to, // номер телефона пользователя
            "ip" => $_SERVER["REMOTE_ADDR"], // ip адрес пользователя
            "api_id" => config('sms.api_key')
        )));
        $body = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($body);
        if ($json) {
            if ($json->status == "OK") { 
                return $json;
            } else {
                // dd($json);
                // $code_error = $json->status_code;
                // $message = $json->status_text;

                // $info_error = [
                //     'code' => $code_error,
                //     'message' => $message
                // ];
                // dd($info_error);

                // return false;
                return $json;
            } 
        } else {
            return 444;
        }
    }
}