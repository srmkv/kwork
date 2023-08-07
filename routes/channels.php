<?php

use Illuminate\Support\Facades\Broadcast;

use App\Broadcast\ChatChannel;
/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });


// Broadcast::channel('chat', ChatChannel::class);
// Broadcast::channel('public.chat.1', ChatChannel::class);

Broadcast::channel('chatbox.{id}', function ($user, $id){
	// $user = \App\Models\User::find(133);
    // return $user;

    // return true;

    // return $user;
    // $user = \App\Models\User::find(133);
    return [

    	'room_id' => $id, 
    	'user_id' => $user->id
    ];
});

// Broadcast::channel('private.chatbox-user.{id}', function($user, $id){

// 	return true;
// });