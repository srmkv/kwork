<?php
namespace App\Services\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Str;

//models
use App\Models\User;
use App\Models\Email;

use Illuminate\Support\Facades\Auth;
use App\Mail\VerifyEmail;

class UserEmailService {



    public function letter(User $user, $email, $statusEmail)
    {   


        $mail_db = \DB::table('mails')->where('user_mail', $email)->get()->first();
        
        if($mail_db == null){
            $mail = new Email;
        } else {

            $mail = EMail::find($mail_db->id);
        }

        
        $mail->user_id = $user->id;
        $mail->user_mail = $email;

        $mail->status = $statusEmail;

        $mail->verify_code_mail = Str::random(15);
        $mail->save();

        $user->email = $email;
        $user->save();

        \Mail::to($mail->user_mail)->send(new verifyEmail($mail->verify_code_mail));



    	return response()->json([
            "message" => "Вам отправлено письмо, верифицируйте свой email..",
            "code" => 201,
        ],201);




    }






}