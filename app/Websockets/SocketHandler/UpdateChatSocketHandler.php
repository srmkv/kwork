<?php

namespace App\Websockets\SocketHandler;

// use App\Websockets\SocketHandler\BaseSocketHandler;

// use Exceceptions;
// use Ratchet\ConnectionInterface;
// use Ratchet\RFC6455\Messaging\MessageInterface;
// use Ratchet\WebSocket\MessageComponentInterface;


use App\Http\Resources\Chat\MessageResource;
// use App\Repositories\PostRepository;
use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;



class UpdateChatSocketHandler extends BaseSocketHandler implements MessageComponentInterface
{   

    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }



    //происходит когда юзер отправил сообщение 
    function onMessage(ConnectionInterface $from, MessageInterface $msg)
    {   
        // $body = collect(json_decode($msg->getPayload(), true));

        // // dump($msg->getPayload());
        // $payload = $body->get('payload');

        // // dump($payload);
        // // $

        // // $response = make::MessageResource($payload)
        // $response = [

        //     'messageFromServer' => 'Hello from server!',
        //     'user_id' => 'verified_1'
        // ];

        // // $from->send('hello!');
        // $from->send('hello');




        $body = collect(json_decode($msg->getPayload(), true));

        $payload = $body->get('payload');
        $id = $body->get('id');

        dump($payload, $id);

        // $post = Post::query()->findOrFail($id);

        // $repo = new PostRepository();

        // $updated = $repo->update($post, $payload);

        // $response = (new PostResource($updated))->toJson();

        // $response = $payload->toJson();

        // $gg = [

        //     'test' => '100',
        //     'user' => 'Eg'
        // ];

       $gg = \DB::table('users')->where('id', 133)->get()->toJson();

        $from->send($gg);


    }





}
