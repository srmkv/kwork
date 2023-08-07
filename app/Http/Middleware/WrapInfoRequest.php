<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WrapInfoRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {   

        // dd(2);

        // test success 90%

        // \DB::table('dev_requests')->insert([
        //     'request_type' => 'reg',
        //     'request_body' => $request
        // ]);
        

        \DB::table('dev_requests')->insert([
            'request_type' => 'file',
            'request_body' => $request
        ]);




        return $next($request);
    }
}


// https://back.qualifiterra.ru/storage/images/avatars/2-2.jpg

// https://back.qualifiterra.ru/storage/pdf_preview/Zajavlenie_Det_Obrazec_351.png
