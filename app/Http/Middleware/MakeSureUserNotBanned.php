<?php

namespace App\Http\Middleware;

use App\Models\BannedIpAddress;
use App\Services\UserIpServices;
use Closure;
use Illuminate\Http\Request;

class MakeSureUserNotBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $userIpServices = new UserIpServices();

        $bannedIpAddress = BannedIpAddress::where('ip', $userIpServices->getUserIp())->first();

        if ($bannedIpAddress){
            return response()->json([
                'access' => 'this IP is located in the bath',
                'date' => 'the date when the user will be unbanned ' . $bannedIpAddress->created_at->addDays(1),
            ]);
        }

        return $next($request);
    }
}
