<?php

namespace Database\Seeders;

use App\Models\Course\LessonType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LessonType::insert([
            [
                'title' => 'Видео',
                'slug' => \Str::slug('Видео'),
            ],
            [
                'title' => 'Презентация',
                'slug' => \Str::slug('Презентация'),
            ],
            [
                'title' => 'Трансляция',
                'slug' => \Str::slug('Трансляция'),
            ],
            [
                'title' => 'Текст',
                'slug' => \Str::slug('Текст'),
            ],
            [
                'title' => 'Тест',
                'slug' => \Str::slug('Тест'),
            ],
        ]);
    }
}
