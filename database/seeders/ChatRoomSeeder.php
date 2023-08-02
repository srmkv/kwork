<?php

namespace Database\Seeders;

use App\Models\Chat\ChatRoom;
use App\Models\Course\BidState;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ChatRoom::insert([
            [
                'id' => 17,
                'type_room' => 'business_order',
                'profiles' => '[51, 53]',
                'title' => 'Чат студноты #0',
                'avatar' => 'https://back.qualifiterra.ru/example.png',
                'author' => '[{"type":"business","company_id": 25}]',
                'business_order_id' => null,
                'order_id' => null,
            ],
            [
                'id' => 18,
                'type_room' => 'business_order',
                'profiles' => '[51, 56]',
                'title' => 'Чат студноты #0',
                'avatar' => 'https://back.qualifiterra.ru/example.png',
                'author' => '[{"type":"business","company_id": 25}]',
                'business_order_id' => null,
                'order_id' => null,
            ],
            [
                'id' => 19,
                'type_room' => 'business_order',
                'profiles' => '[51, 56]',
                'title' => 'Чат студноты #10',
                'avatar' => 'https://back.qualifiterra.ru/example.png',
                'author' => '[{"type":"business","company_id": 25}]',
                'business_order_id' => null,
                'order_id' => null,
            ],
            [
                'id' => 21,
                'type_room' => 'business_order',
                'profiles' => '[51, 68]',
                'title' => 'Чат студноты #10',
                'avatar' => 'https://back.qualifiterra.ru/example.png',
                'author' => '[{"type":"business","company_id": 25}]',
                'business_order_id' => null,
                'order_id' => null,
            ],
            [
                'id' => 22,
                'type_room' => 'business_order',
                'profiles' => '[51, 56]',
                'title' => 'Чат студноты #10',
                'avatar' => 'https://back.qualifiterra.ru/example.png',
                'author' => '[{"type":"business","company_id": 25}]',
                'business_order_id' => null,
                'order_id' => null,
            ],
            
            
        ]);
    }
}
