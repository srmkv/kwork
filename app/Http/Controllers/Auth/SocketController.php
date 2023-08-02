<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Closure;

use App\Models\User;

class SocketController extends Controller
{
    public function authUser(Request $request)
    {
        // return true;

        // $token = '1343|FvOqHvgEmDDNQ1sLCGjmi6MkXpExPbp9uHJvS2Ki';
        // $headers = array('Authorization: Bearer ' . $token);
        // header('Authorization: Bearer ' . $token, false);
        // header_remove();
        // dd(getallheaders());

        // $response = \Http::post('https://back.qualifiterra.ru/api/auth/socket/private-channel');


        // dd($response->headers());
        // $token = '1343|FvOqHvgEmDDNQ1sLCGjmi6MkXpExPbp9uHJvS2Ki';
        // $headers = array('Authorization: Bearer ' . $token);
        // header('Authorization: Bearer ' . $token, false);

        return User::find(133);
        // return $next($request);

    }
}
