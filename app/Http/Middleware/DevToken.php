<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests;

// class DevToken extends Middleware


class DevToken implements AuthenticatesRequests
{   


    public function handle(Request $request, Closure $next, ...$guards)
    {   
        $bear = $request->bearerToken();
        if($bear !== null) {
            $dev = \DB::table('dev_tokens')
                            ->where('user_token', $request->bearerToken())
                            ->value('user_token');
        }

        if($dev == null) {
            return response()->json('dev no hack', 403);

        } elseif( $dev !== null && $bear == $dev ) {

            return $next($request);
        } 
    }

}
