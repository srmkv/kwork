<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;
use App\Models\Chat\Message;




class MessageSend implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $user;
    public $message;


    public function __construct(User $user, Message $message)
    {   


        $this->user = $user;
        $this->message = $message;
    }

    
    // public function broadcastOn()
    // {   
    //     //если мы будем использовать канал только для конкретного юзера ( уведомления и т.д)
    //     // return new PrivateChannel('chat');
    //     return new PresenceChannel('chat');
    // }


    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');

        return new Channel('public.chat.1');
    }

    //вернуть имя канала
    public function broadcastAs()
    {
        return 'commonChat';
    }


    //послать нагрузку в ивент

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            // 'user_id' => $this->user->id
        ];
    }
}
