/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
// import { createApp } from 'vue';

// window.Vue = require('vue');
/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */




// const appTest = createApp({});

// import ExampleComponent from './components/ExampleComponent.vue';
// appTest.component('example-component', ExampleComponent);
// appTest.mount('#app');


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))


// const app = new Vue({
//     el: '#app',

//     data: {
//         messages: [],
//         users: [],
//     },

//     created() {
//         this.fetchMessages();

//         Echo.join('chat')
//             .here(users => {
//                 this.users = users;
//             })
//             .joining(user => {
//                 this.users.push(user);
//             })
//             .leaving(user => {
//                 this.users = this.users.filter(u => u.id !== user.id);
//             })
//             .listenForWhisper('typing', ({id, name}) => {
//                 this.users.forEach((user, index) => {
//                     if (user.id === id) {
//                         user.typing = true;
//                         this.$set(this.users, index, user);
//                     }
//                 });
//             })
//             .listen('MessageSent', (event) => {
//                 this.messages.push({
//                     message: event.message.message,
//                     user: event.user
//                 });

//                 this.users.forEach((user, index) => {
//                     if (user.id === event.user.id) {
//                         user.typing = false;
//                         this.$set(this.users, index, user);
//                     }
//                 });
//             });
//     },

//     methods: {
//         fetchMessages() {
//             axios.get('/messages').then(response => {
//                 this.messages = response.data;
//             });
//         },

//         addMessage(message) {
//             this.messages.push(message);

//             axios.post('/messages', message).then(response => {
//                 console.log(response.data);
//             });
//         }
//     }
// });



// const channel = Echo.join('chatbox.2')
//   .here((users) => {

//     console.log(11);
//   })

//   .joining((users) => {

//       console.log(user.name);

//   })

//   .leaving((user) => {
//     //
//   })
//   .error((error)=> {
//       console.error(error);

//   })


const channel = Echo.join('chatbox.2');

channel.here((users)=> {
  console.log({users}) 
  console.log('subscribed presence !');
})

.joining((user) => {
  console.log({user}, 'joined')
})

.listen('ChatMessageEvent', (e) => {
  console.log(e);
})
.leaving((user) => {

  console.log({user}, 'leaving')
})

// channel.subscribed(() => {
//     console.log('sub');
// }).listen('ChatMessageEvent', (event) => {
//     console.log(22);
// })




// Echo.join('private-chatbox-user.1')
//   .listen('ChatMessageEvent', (e) => {
//     console.log(e.message);
// });

// import WebSocket from 'ws';


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
//            console.log(event);

//        }

       
// }



// Connection id 46548315.613018956 

// sending message {"event":"log-message",
//     "channel":"private-websockets-dashboard-api-message",
//     "data":{"type":"api-message","time":"02:37:10",
//     "details":"Channel: public.chat.1, Event: commonChat",
//     "data":"{\"user\":\"\\u0422\\u0435\\u0445. \\u043f\\u043e\\u0434\\u0434\\u0435\\u0440\\u0436\\u043a\\u0430\"}"}
// }


//user- тех.поддержка