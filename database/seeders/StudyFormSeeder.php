<?php

namespace Database\Seeders;

use App\Models\Course\Filter;
use App\Models\Course\StudyForm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudyFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StudyForm::insert([
            [
                'title' => 'Очная',
                'slug' => \Str::slug('Очная'),
                // 'filter_id' => Filter::where('title','Форма обучения')->first()->id,
            ],
            [
                'title' => 'Дистанционная',
                'slug' => \Str::slug('Дистанционная'),
                // 'filter_id' => Filter::where('title','Форма обучения')->first()->id,
            ],
            [
                'title' => 'Смешанная',
                'slug' => \Str::slug('Смешанная'),
                // 'filter_id' => Filter::where('title','Форма обучения')->first()->id,
            ]
        ]);
    }
}
