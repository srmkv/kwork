<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Profiles\ProfileIndividual;



class LoginController extends Controller
{

    // FOR TEST FRONT

    public function deleteForceUser(Request $request)
    {       

        $user = User::where('phone', $request->phone)->first();

        $profile = ProfileIndividual::where('phone', $request->phone)->first(); 




        if ($user) {
            $user->delete();

            echo 'delete user force!';
        } else {

            echo 'no user';
            echo " ";
        }


        if ($profile) {
            $profile->delete();

            echo  'delete prof. force!';
        } else {

            echo 'no profile.';
        }

    }
    




    // ВЫНЕСТИ В ТРЕЙТ ПОДОБНЫЕ ФУНКЦИИ
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }
    
    // ВЫНЕСТИ В ТРЕЙТ ПОДОБНЫЕ ФУНКЦИИ
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
    



    public function login(Request $request): \Illuminate\Http\JsonResponse
    {   

        // Временно !!! Пока не будет готов seeder с дефолтным юзером
        if (!Auth::attempt($request->only('phone', 'password')))
        {
            return response()->json([
                'message' => 'Неверный логин или пароль, заполните поля заново'
            ], 401);
        }   


        if(Auth::attempt(['phone' => $request->phone, 'password' => $request->password])){ 
            $authUser = Auth::user(); 
            $success['token'] =  $authUser->createToken('auth_token')->plainTextToken;

            // 0.

            \DB::table('dev_tokens')->insert([

                'user_id' => auth()->user()->id,
                'type_user' => 'individual/login',
                'user_token' => $success['token']
            ]); 



            $success['name'] =  $authUser->name;
            

            return response()->json([
                'message' => 'success sign-in user',
                'code' => 200,
                'token' => $success['token'],
                'userid' => auth()->user()->id
            ], 200);



        } 

        //todo Убрать как будет сиид !!!!!
        // return response()->json([
        //     'message' => 'success sign-in user',
        //     'code' => 200,
        //     'token' => \DB::table('dev_tokens')->first()->user_token,
        //     'userid' => User::first()->id
        // ], 200);

    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
           'response' => 'success'
        ]);


       auth()->user()->tokens()->delete();
       
    }
}
