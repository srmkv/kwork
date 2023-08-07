<?php
namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{

    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        // если не получили ответ, след. запрос запрещен?
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \Fruitcake\Cors\HandleCors::class,

    ];


    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],


        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api', //429
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Fruitcake\Cors\HandleCors::class,

        ],


        
    ];


    protected $routeMiddleware = [
        'dev_token' => \App\Http\Middleware\DevToken::class,
        'request_info' => \App\Http\Middleware\WrapInfoRequest::class, // отладочный мидл
        'auth' => \App\Http\Middleware\Authenticate::class,
        
        //должны быть все абилки у юзера
        // 'abilities' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,

        // пропустит если есть хотябы 1 "абилка"
        // 'ability' => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class, 
        
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,

        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'role'  =>  \App\Http\Middleware\RoleMiddleware::class,
    ];
}

