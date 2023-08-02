<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Profiles\ProfileIndividual;

use App\Services\Auth\AdminAuthService;




class AdminLoginController extends Controller
{
    public function __construct(AdminAuthService $auth)
    {
        $this->auth = $auth;
    }


    public function login(Request $request)
    {   
        return $this->auth->checkAttempt($request->login, $request->password);
    }

}
