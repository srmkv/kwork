<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

use App\Http\Middleware\Authentificate;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes();

       // Broadcast::routes(['middleware' => 'dev_token']);
       // Broadcast::routes(["middleware" => "dev_token"]) ;


       Broadcast::routes(['middleware' => ['auth:sanctum']]);




        require base_path('routes/channels.php');
    }
}
