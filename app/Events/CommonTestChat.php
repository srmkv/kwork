<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommonTestChat implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */


    public $message;



    // public function __construct($message)
    // {
    //     $this->message = $message;
    // }




    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');

        // return new Channel('public.chat.1');


        return new Channel('chatbox');

        

        // return new PrivateChannel('support');
    }

    // // имя канала
    public function broadcastAs()
    {
        return 'chatbox';
    }


    //послать нагрузку в ивент

    public function broadcastWith()
    {
        return [

            'user' => 'Тех. поддержка'
        ];
    }

}
