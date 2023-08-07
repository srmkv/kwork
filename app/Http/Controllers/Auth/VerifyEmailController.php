<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;


use App\Mail\VerifyEmail;

use Illuminate\Auth\Events\Registered;

class VerifyEmailController extends Controller
{
    // если бы верификация была по почте

    // public function __invoke(Request $request): RedirectResponse
    // {
    //     $user = User::find($request->route('id'));

    //     if ($user->hasVerifiedEmail()) {
    //         return redirect(env('FRONT_URL') . '/email/verify/already-success');
    //     }

    //     if ($user->markEmailAsVerified()) {
    //         event(new Verified($user));
    //     }

    //     return redirect(env('FRONT_URL') . '/email/verify/success');
    // }




    public function sendVerifyMail(Request $request)
    {
        // $user = User::find($request->user_id);

        $user = User::find(Auth::id());
        $email = $request->email;

        // $order = 't'

            // Отправляем заказ ...
        // $pin = '153485943759';
        \Mail::to('tregubenko.e.d@appfox.ru')->send(new verifyEmail($pin));

        // $user::find(133);
        // $user->email = 's';
        // Mail::to($user)->send(new YourMail);


        

    }
}