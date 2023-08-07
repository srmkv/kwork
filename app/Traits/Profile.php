<?php
namespace App\Traits;

use App\Models\User;

use App\Models\Profiles\ProfileBuiseness;
use App\Models\Profiles\ProfileIndividual;
use App\Models\Profiles\ProfileSelfEmployed;

trait Profile
{
    public static function getUserId($profile_id)
    {
        $profile = ProfileIndividual::find($profile_id);
        return $profile->user_id;
    }

    public static function getProfileId($user_id)
    {
        $user = User::find($user_id);
        return $user->individualProfile->id;
    }
}