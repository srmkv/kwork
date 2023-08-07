<?php

namespace Database\Seeders;

use App\Models\Course\UseTechnology;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UseTechnologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UseTechnology::insert([
            [
                'title' => 'Электронное обучение'
            ],
            [
                'title' => 'Дистанционные технологии'
            ],
        ]);
    }
}
