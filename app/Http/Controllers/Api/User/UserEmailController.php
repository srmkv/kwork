<?php
namespace App\Http\Controllers\Api\User;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Email;


use App\Mail\VerifyEmail;

use Illuminate\Support\Facades\Auth;

use App\Services\User\UserEmailService;



class UserEmailController extends Controller
{   


    public function __construct(UserEmailService $sendmail)
    {
        $this->sendMail = $sendmail;
    }



    public function sendVerifyMail(Request $request)
    {

        $user = User::find(Auth::id());
        // dd($user);
        //если уже что то делали с email адресом
        if ($user->mail()->exists()) {

            $mail = $user->mail;
            $status_email = $mail->status;

            switch ($status_email) {
                // пытаем еще раз отправить письмо на почту
                case 'pending':
                    if($request->email == $mail->user_mail) {

                        return response()->json([
                            "message" => "Вам уже отправлено письмо, проверьте почту..",
                            "code" => 201,
                        ],201);

                    } else {
                        return $this->sendMail->letter($user,$request->email,$status_email);
                    }

                    break;
                
               
                case 'change':

                    if($request->email == $mail->user_mail){

                        return response()->json([
                            "message" => "Ваша почта уже подтверждена..",
                            "code" => 201,
                        ],201);


                    } else {
                        // меняем существующий емейл на новый
                       return $this->sendMail->letter($user,$request->email,$status_email);
                    }

                    break;

                default:

                    return 'no means';

                    break;
            }


        } else {

           // Если еще ни разу не редактировали емейл
           $status_email = 'pending';
          return  $this->sendMail->letter($user,$request->email,$status_email);
        }






    }


    public function veryfyPinMail(Request $request) {

        $pin = $request->pin;

        $mail_db = \DB::table('mails')->where('verify_code_mail', $pin)->get()->first();

        $mail = Email::find($mail_db->id);
        $user = User::find($mail->user_id);


        $user->email = $mail->user_mail;
        $user->email_verified_at = now();

        // $user->individualProfile->email = $mail->user_mail;

        \DB::table('profiles_individuals')
            ->where('user_id', $user->id)
            ->update(['email' => $mail->user_mail ]);





        $user->save();



        $mail->status = 'change';
        $mail->save();

        // return 110;
        $url = 'https://qualifiterra.ru/profile/personal-data';

        return \Redirect::to($url);


    }



}