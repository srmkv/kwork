<?php

namespace App\Http\Controllers\Chat;

use Illuminate\Contracts\Founation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Chat\Message;
use App\Models\Chat\ChatRoom;

use App\Events\ChatMessageEvent;

use App\Http\Requests\MessageRequest;

use App\Http\Resources\Chat\ChatRoomResource;

use App\Services\Chat\BusinessChatService;

class ChatController extends Controller
{   

    public function __construct(BusinessChatService $businessChat)
    {
        $this->businessChat = $businessChat;
    }

    // удалить после фул дебага
    public function index()
    {
        $user = User::find(133);

        return view('chat')->with('user', $user);
    }


    // public function allUserMessage(Request $request)
    // {   
    //     $user = User::find(Auth::id());

    //     return Message::query()
    //         ->where('user_id', $user->id)
    //         ->get();

    // }




    public function fetchMessages(Request $request, $room_id)
    {
        // return Message::with('user')->where('chat_room_id', $room_id)->get();

            $user = User::find(Auth::id());

            return Message::query()
                ->where('user_id', $user->id)
                ->where('chat_room_id', $room_id)
                ->get();


    }

    public function getUserChatRoom(Request $request, $business_order_id)
    {   

        $user = User::find(Auth::id());

        $rooms = auth()->user()->chats()->where('business_order_id', $business_order_id);


        // return $rooms->get();

        return ChatRoomResource::collection($rooms->get());
    }

    public function sendMessage(Request $request)
    {
        $message = auth()->user()->messages()->create([
            'message' => $request->message,
            'chat_room_id' => $request->room_id
        ]);

        // для тех пользователей которые прочитали сообщение (находятся в чат руме) проставим метку
        // протестировать на фронте        
        // $user->unreadMessages()->updateExistingPivot($message->id, [
        //     'read_at' => true,
        // ]);

        //транслируем событие всем в чат руме кроме создателя сообщения
        broadcast(new ChatMessageEvent($message))->toOthers();
        return 
            [
                'status' => 'Message Sent!',
                'message' => $message

            ];
    }


    public function messagesIntoRoom(Request $request, $room_id)
    {
        return \DB::table('messages')->where('chat_room_id', $room_id)->get();
    }

    public function createChatRoom(Request $request)
    {
        $user = User::find(Auth::id());
        $room = new ChatRoom;
        $room->author = $request->author;
        $room->profiles = $request->profiles;
        $room->title = $request->title;
        $room->avatar = $request->tmp_avatars;
        $room->type_room = $request->type_room;
        $room->business_order_id = $request->business_order_id;
        $room->save();
        $this->businessChat->updateUsersChatRoom($room, $user->id);

        return ChatRoomResource::make($room);
    }


    public function editChatRoom(Request $request, $room_id)
    {
        $user = User::find(Auth::id());
        $room = ChatRoom::find($room_id);
        // $room->author = $request->author;
        $room->profiles = $request->profiles;
        $room->title = $request->title ?? $room->title;
        $room->avatar = $request->tmp_avatars ?? $room->avatar;
        $room->type_room = $request->type_room ?? $room->type_room;
        $room->save();
        $this->businessChat->updateUsersChatRoom($room, $user->id);
        return ChatRoomResource::make($room);
    }

    public function deleteChatRoom(Request $request, $room_id)
    {
        $user = User::find(Auth::id());
        
        $room = ChatRoom::find($room_id);
        $this->businessChat->updateUsersChatRoom($room, 'delete');

        $room->delete();

        return response()->json([
            "message" => "Чат комната была успешно удалена..",
        ], 201);

    }



}

