<?php

namespace Database\Seeders;

use App\Models\Course\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Подразделы FAQ
     * @return void
     */
    public function run()
    {
        Faq::insert([
            [
                'title' => 'Курсы',
                'course_id' => 1
            ],
            [
                'title' => 'Оплата',
                'course_id' => 1
            ],
            
        ]);
    }
}
