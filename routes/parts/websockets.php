<?php

use \App\Http\Controllers\Auth\SocketController;

Route::controller(SocketController::class)->group(function(){
    // авторизация в частном канале
    Route::post('/auth/socket/private-channel', 'authUser');

});