<?php

namespace Database\Seeders;

use App\Models\Doc;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Doc::insert([
            [
                'title' => 'Паспорт',
            ],
            [
                'title' => 'СНИЛС',
            ],
            [
                'title' => 'Свидетельство о браке',
            ],
            [
                'title' => 'Трудовая',
            ],
            [
                'title' => 'Дополнительные документы',
            ],
            
        ]);
    }
}
