<?php

namespace App\Http\Controllers\Api\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//req
use App\Http\Requests\VerifiedPinRequest;
use App\Http\Requests\ChangeNumberPhoneRequest;
use App\Http\Requests\PinRequest;


//services
use App\Services\Auth\Pin\CreateVerifyPinService;


use App\Models\User;
use App\Models\Profiles\ProfileIndividual;


class UserPhoneController extends Controller
{   
    public function __construct(CreateVerifyPinService $sendpin)
    {
        $this->verifypin = $sendpin;
    }


    //первичная отправка
    public function changePhone(ChangeNumberPhoneRequest $request)
    {
            return $this->verifypin->changePhoneUser($request->phone);

    }

    //повторная отправка
    public function resendChangePhone(PinRequest $request)
    {
            return $this->verifypin->checkCurrentPin($request->pin_id);

    }

    // верифицируем пин
    public function verifyChangePhone(PinRequest $request)
    {
            return $this->verifypin->checkVerifyPin($request->pin, $request->pin_id, $request->phone);
    }



}
