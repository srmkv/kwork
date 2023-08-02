<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\User;
use App\Models\Profiles\ProfileSelfEmployed;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use Illuminate\Support\Facades\Auth;

class ProfileSelfEmployedController extends Controller
{
    public function editProfile(Request $request)
    {
        $user = User::find(Auth::id());
        $type = $request->type_profile;
        $user->selfEmployedProfile()->updateOrCreate([
                'user_id' => $user->id,
                'type_profile' => $type
            ],
            [
                'country_id' => $request->country_id,
                'activity_type' => $request->activity_type,
                'tax_type_id' => $request->tax_type_id ?? 1,
                // 'status' => $request->status,
                'inn' => $request->inn,
                'ogrnip' => $request->ogrnip,
                'date_registration' => $request->date_registration,
                'full_title' => $request->full_title,
                'bik' => $request->bik,
                'title_bank' => $request->title_bank,
                'bank_account' => $request->bank_account,
                'correspondent_account' => $request->correspondent_account,
                'phone' => $request->phone,
                'email' => $request->email,
                'type_profile' => $request->type_profile 
            ]
        );


        return $this->byTypeProfile($type, $user->id);

    }


    public function getProfile(Request $request)
    {
        $user = User::find(Auth::id());

        if($user->selfEmployedProfile) {
            $type = $request->type_profile;
            return $this->byTypeProfile($type, $user->id);
        } else{
            return response()->json([
                "message" => "Попробуйте сначала создать профиль..",
                "code" => 403, 
            ],403);
        }
    }


    public function byTypeProfile($type, $user_id){
            return \DB::table('profiles_self_employed')->where('type_profile', $type)->where('user_id', $user_id)->first();
    }


    public function loadLogo(Request $request)
    {   
        $user = User::find(Auth::id());
        $type = $request->type_profile;
        $profile = ProfileSelfEmployed::find($this->byTypeProfile($type, $user->id)->id);

        $profile->addMediaFromRequest('logo')->withCustomProperties([
            'profile_ip_id' => intval($profile->id),
            'user_id' => intval($user->id),
            'type_profile' => $type,
        ])->toMediaCollection('profile_ip_logo');


        $media_id = $profile->getMedia('profile_ip_logo')->last()->id;

        $profile->media_logo_id = $media_id;
        $profile->save();




        return response()->json([
            "message" => "Лого успешно загружен..",
            "code" => 201,
            "media_id" => $profile->getMedia('profile_ip_logo')->last()->id,
            "type_profile" => $type
            
        ],201);

    }

    public function showLogo(Request $request)
    {   
        $user = User::find(Auth::id());
        
        $type = $request->type_profile;
        $profile = ProfileSelfEmployed::find($this->byTypeProfile($type, $user->id)->id);

        $logo = $profile->getMedia('profile_ip_logo', [
            'user_id' => intval($user->id),
            'type_profile' => $type
        ])->last();

        $mime_logo = file_get_contents($logo->getPath());

        return response($mime_logo)->withHeaders([
            'Content-Type' => mime_content_type($logo->getPath())
        ]);


    }




}
