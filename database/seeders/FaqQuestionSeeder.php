<?php

namespace Database\Seeders;

use App\Models\Course\FaqQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FaqQuestion::insert([
            [
                'title' => 'Что такое курс?',
                'faq_id' => 1
            ],
            [
                'title' => 'Как производится оплата?',
                'faq_id' => 2
            ],
            
        ]);
    }
}
