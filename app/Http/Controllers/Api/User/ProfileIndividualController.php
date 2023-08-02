<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryCourseStoreRequest;
//req

use App\Http\Requests\ChangeNumberPhoneRequest;
use App\Http\Requests\UserProfileRequest;


// use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Mail\VerifyEmail;

//models
use App\Models\User;
use App\Models\Profiles\ProfileIndividual;
use App\Models\Pin;


//facades
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

//resources
use App\Http\Resources\User\UserProfileResource;
use App\Http\Resources\User\IndividualResource;

// use Carbon\Carbon;


class ProfileIndividualController extends Controller
{   

    public function profile(Request $request)
    {   
        $user_id = Auth::id();
        $user = User::with('individualProfile')->find($user_id);
        $profile = ProfileIndividual::where('user_id','like', $user_id)->first();

        if( !isset($request->last_name) && 
            !isset($request->name)      && 
            !isset($request->date_birthday) &&
            !isset($request->phone) &&
            !isset($request->middle_name)
             ){
                return UserProfileResource::make($user);
            }


        if (isset($request->last_name))  {
            $profile->lastname = $request->last_name;
            $profile->save();
        }


        if (isset($request->name)) {
            $profile->name = $request->name;
            $profile->save();
        }

        if (isset($request->date_birthday)) {

            $profile->date_birthday = new Carbon($request->date_birthday);
            $profile->save();
        }

        
       if (isset($request->email)){
           $profile->email = $request->email;
           $profile->save();
        } 


        if (isset($request->middle_name)) {
            $profile->middle_name = $request->middle_name;
            $profile->save();
        }


        return response()->json([
            'message' => 'profile fixed changes',
        ],200);


    }



}
