@extends('layouts.app')

@section('content')
    @php
        // $token = '1345|Hp9nAtoy9C6jgnYBk5MTA5inQSyxvyNVdX32ZnaP';
        // $headers = array('Authorization: Bearer ' . $token);
        // header('Authorization: Bearer ' . $token, false);
        // $id = array_rand(['133','149','166','165','168','168','172','174'], 1);

        $ids = collect(['133','149','166','165','168','168','172','174']);
        $user = \App\Models\User::find($ids->random());
        Auth::loginUsingId($user->id);
        
    @endphp
    <div class="container chats">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="card card-default">
                    <div class="card-header">Chats</div>

                    <div class="card-body">
                        <chat-messages :messages="messages"></chat-messages>
                    </div>
                    <div class="card-footer">
                        <chat-form
                        @messagesent="addMessage"
                                
                        >
                            
                        </chat-form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <ul class="list-group">
                    <li class="list-group-item" v-for="user in users">
                        {{ $user->name }} <span v-if="user.typing" class="badge badge-primary">typing...</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div> ТЕСТ ЧАТ</div>

    @if (Auth::check())
       ДА
    @else
        НЕТ
    @endif


    <script type="text/javascript">
        
        // updateChat();

        // function updateChat(){

        //     const socket = new WebSocket(`wss://${window.location.hostname}:6001/socket/update-chat?appKey=87a9cb4256cc688b21a7`);


        //     socket.onopen = function (event){
        //            console.log('on open!!');

        //            socket.send(JSON.stringify({
        //                id: 1,
        //                payload: {
        //                    title: 'abc123',
        //                }
        //            }))
        //        }

        //        socket.onmessage = function (event) {
        //            // console.log(event.data);
        //            console.log(event.data);

        //        }

               
        // }

        // import Echo from 'laravel-echo';

        // window.Echo = new Echo({
        //     broadcaster: 'pusher',
        //     key: '87a9cb4256cc688b21a7',
        //     wsHost: window.location.hostname,
        //     wssPort: 6001,
        //     encrypted: true,
        //     // disableStats: true,
        //     enabledTransports: ['ws', 'wss'],
        //     // authEndpoint: 'api/auth/socket/private-channel'
        //     // auth: {
        //     //     headers: {
        //     //         Accept: 'application/json',
        //     //         Authorization: 'Bearer 1343|FvOqHvgEmDDNQ1sLCGjmi6MkXpExPbp9uHJvS2Ki',
        //     //     }
        //     // }

        //     auth: {
        //          headers: {
        //            Authorization: 'Bearer 658f521a88397e55f5d03d1a5cf1c345a5d3286e40b3f0bde1213ffc036e7b3d',
        //          }
        //     },

            
        // });


    </script>
@endsection