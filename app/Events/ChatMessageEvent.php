<?php

namespace App\Events;

use App\Models\User;
use App\Message;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $message;
    public $user;

    public function __construct(string $message)
    {   
        $this->message = $message;
    }


    public function broadcastOn()
    {
        // return new PresenceChannel('chatbox.2');
        return new PresenceChannel('chatbox.' . $this->message->chat_room_id);
    }



    public function broadcastWith()
    {
        return [

            'mesage' => $this->message
        ];
    }
}
