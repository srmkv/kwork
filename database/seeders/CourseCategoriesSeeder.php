<?php

namespace Database\Seeders;

use App\Models\Course\CategoryCourse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CategoryCourse::insert([
            [//1
                'title' => 'Взрослые',
                'slug' => \Str::slug('Взрослые'),
                'description' => 'description',
                'seo_title' => 'seo_title',
                'seo_description' => 'seo_description',
                'seo_keywords' => 'seo_keywords',
                'parent_id' => null,
                'tag_id' => 2,
            ],
            [//2
                'title' => 'Дети',
                'slug' => \Str::slug('Дети'),
                'description' => 'description2',
                'seo_title' => 'seo_title2',
                'seo_description' => 'seo_description2',
                'seo_keywords' => 'seo_keywords2',
                'parent_id' => null,
                'tag_id' => 1,
            ],
            [//-3
                'title' => 'Медицина',
                'slug' => \Str::slug('Медицина'),
                'description' => 'description',
                'seo_title' => 'seo_title',
                'seo_description' => 'seo_description',
                'seo_keywords' => 'seo_keywords',
                'parent_id' => 1,
                'tag_id' => 2,
            ],
            [//-4
                'title' => 'Спорт',
                'slug' => \Str::slug('Спорт'),
                'description' => 'description',
                'seo_title' => 'seo_title',
                'seo_description' => 'seo_description',
                'seo_keywords' => 'seo_keywords',
                'parent_id' => 1,
                'tag_id' => 2,
            ],
            [//-5
                'title' => 'ИТ',
                'slug' => \Str::slug('ИТ'),
                'description' => 'description',
                'seo_title' => 'seo_title',
                'seo_description' => 'seo_description',
                'seo_keywords' => 'seo_keywords',
                'parent_id' => 2,
                'tag_id' => 2,
            ],
            [//-6
                'title' => 'Дошкольное образование',
                'slug' => \Str::slug('Дошкольное образование'),
                'description' => 'description',
                'seo_title' => 'seo_title',
                'seo_description' => 'seo_description',
                'seo_keywords' => 'seo_keywords',
                'parent_id' => 2,
                'tag_id' => 2,
            ],
            [//-7
                'title' => 'Для школьников',
                'slug' => \Str::slug('Для школьников'),
                'description' => 'description',
                'seo_title' => 'seo_title',
                'seo_description' => 'seo_description',
                'seo_keywords' => 'seo_keywords',
                'parent_id' => 2,
                'tag_id' => 2,
            ],
            [//--8
                'title' => 'Лепим из песка',
                'slug' => \Str::slug('Лепим из песка'),
                'description' => 'description',
                'seo_title' => 'seo_title',
                'seo_description' => 'seo_description',
                'seo_keywords' => 'seo_keywords',
                'parent_id' => 6,
                'tag_id' => 2,
            ],
            [//--9
                'title' => 'Рукоделие',
                'slug' => \Str::slug('Рукоделие'),
                'description' => 'description',
                'seo_title' => 'seo_title',
                'seo_description' => 'seo_description',
                'seo_keywords' => 'seo_keywords',
                'parent_id' => 6,
                'tag_id' => 2,
            ],
            [//--10
                'title' => 'Web разработка',
                'slug' => \Str::slug('Web разработка'),
                'description' => 'description',
                'seo_title' => 'seo_title',
                'seo_description' => 'seo_description',
                'seo_keywords' => 'seo_keywords',
                'parent_id' => 5,
                'tag_id' => 2,
            ],
            [//--11
                'title' => 'Геникология',
                'slug' => \Str::slug('Геникология'),
                'description' => 'description',
                'seo_title' => 'seo_title',
                'seo_description' => 'seo_description',
                'seo_keywords' => 'seo_keywords',
                'parent_id' => 3,
                'tag_id' => 2,
            ],
            [//--12
                'title' => 'Тренерство',
                'slug' => \Str::slug('Тренерство'),
                'description' => 'description',
                'seo_title' => 'seo_title',
                'seo_description' => 'seo_description',
                'seo_keywords' => 'seo_keywords',
                'parent_id' => 4,
                'tag_id' => 2,
            ],
            [//--13
                'title' => 'Хирургия',
                'slug' => \Str::slug('Хирургия'),
                'description' => 'description',
                'seo_title' => 'seo_title',
                'seo_description' => 'seo_description',
                'seo_keywords' => 'seo_keywords',
                'parent_id' => 3,
                'tag_id' => 2,
            ],
            
        ]);
    }
}
