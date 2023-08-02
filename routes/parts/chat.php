<?php

use App\Http\Controllers\Chat\ChatController;

Route::controller(ChatController::class)->group(function(){
    //сообщения юзера, которые он отправил
    Route::get('chat/messages/out', 'allUserMessage');
    //сообщения юзера в конкретном руме, которые он отправил
    Route::get('chat/fetch-messages/{room_id}', 'fetchMessages');
    
    // сообщения всех пользователей в руме
    Route::get('/chat/room-messages/all/{room_id}', 'messagesIntoRoom');

    Route::get('/messages', 'messages'); // ?) 

    //список чат румов внутри бизнес заявки
    Route::get('/chat/my-rooms/{business_order_id}', 'getUserChatRoom');

    //написать в групповой чат
    Route::post('/chat/send', 'sendMessage');

    // создать чат рум ( для юр. лица)
    Route::post('/chat/business/create', 'createChatRoom');
    // поменять данные в руме
    Route::post('/chat/business/edit/{room_id}', 'editChatRoom');
    // снести рум
    Route::delete('/chat/business/delete/{room_id}', 'deleteChatRoom');

});

