<?php

use Illuminate\Support\Facades\Route;
use App\Events\ChatMessageEvent;
use App\Websockets\SocketHandler\UpdateChatSocketHandler;
use App\Http\Controllers\Chat\ChatController;
//ДЛЯ СОКЕТОВ
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;



// #2

use App\Helpers\Websocket;


// use App\Http\Controllers\Files\UserImagesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/





// use App\Websockets\SocketHandler\UpdateChatSocketHandler;


// напрямую конект к каналам тест
WebSocketsRouter::webSocket('/socket/update-chat',  UpdateChatSocketHandler::class);
// WebSocketsRouter::webSocket('/socket/user-chat',  Websocket::class);
// WebSocketsRouter::webSocket('/socket/update-chat',  \App\Websockets\SocketHandler\UpdateChatSocketHandler::class);



Route::get('/', function () {
    return view('welcome');
});



Route::post('/dev', function(Request $request){
    return view('dev.testactions');
});



// TEST CHAT

Route::get('/test-common-chat' , function(){

    event(new \App\Events\CommonTestChat('hi'));

    return 22;
});

Route::get('/chatbox' , function(){

    $user = \App\Models\User::find(133);
    event(new \App\Events\ChatMessageEvent($user, '1515'));

    // return 22;
});



Route::controller(ChatController::class)->group(function(){
    Route::get('/chat', 'index');
    // Route::get('/messages', 'messages');
    // Route::post('/send', 'sendd');
});

