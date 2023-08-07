<?php

namespace Database\Seeders;

use App\Models\Course\TagSearchCourse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSearchCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TagSearchCourse::insert([
            [
                'title' => 'Переподготовка',
                'slug' => \Str::slug('Переподготовка'),
            ],
            [
                'title' => 'Курсы ИТ',  
                'slug' => \Str::slug('Курсы ИТ'),
            ],
            [
                'title' => 'Мед курсы',  
                'slug' => \Str::slug('Мед курсы'),
            ],
            [
                'title' => 'Курсы',  
                'slug' => \Str::slug('Курсы'),
            ],
            [
                'title' => 'Не дорогие курсы',  
                'slug' => \Str::slug('Не дорогие курсы'),
            ],
            
            
        ]);
    }
}
