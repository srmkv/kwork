<?php

namespace Database\Seeders;

use App\Models\Course\FaqAnswer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FaqAnswer::insert([
            [
                'text' => 'Курсы как курсы...',
                'faq_question_id' => 1,
                'seo_title' => 'seo_title',
                'seo_description' => 'seo_description',
                'seo_keywords' => 'seo_keywords',
            ],
            [
                'text' => 'Газом, только газом за рубасы)',
                'faq_question_id' => 2,
                'seo_title' => 'seo_title',
                'seo_description' => 'seo_description',
                'seo_keywords' => 'seo_keywords',
            ],
            
        ]);
    }
}
