<?php

namespace Database\Seeders;

use App\Models\Course\BidState;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BidStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BidState::insert([
            [
                'name' => 'Черновик'
            ],
            [
                'name' => 'Консультация'
            ],
            
            [
                'name' => 'Подана заявка'
            ],

            [
                'name' => 'Оплачено'
            ],

            [
                'name' => 'Отказано'
            ],

            [
                'name' => 'Возвращены средства'
            ],

            [
                'name' => 'Закрыта'
            ],

            [
                'name' => 'Идёт обучение'
            ],
        ]);
    }
}
