<?php
namespace App\Http\Controllers\Api\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Employee;
use App\Models\Profiles\ProfileBuiseness;
use App\Models\Profiles\ProfileIndividual;
use App\Models\Profiles\ProfileSelfEmployed;
class DashboardController extends Controller
{   
    public function selectProfile(Request $request)
    {   
        $user = User::find(Auth::id());

        $dashbord['individ_profile'] = $user->individualProfile;
        $dashbord['business_profiles'] = $user->ProfileBuiseness;
        $dashbord['ip_profiles'] = $user->selfEmployedProfile;
        return collect($dashbord);

    }

}
