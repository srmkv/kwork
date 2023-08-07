<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {   


        // foreach ($guards as $guard) {
        //             if (Auth::guard($guard)->check()) {

        //                 return 2222;
        //             }
        // }




        if (! $request->expectsJson()) {

            return response()->json([
                "message" => "Это невозможно..",
                "code" => 401       
            ],401);

        } 
    }
}
