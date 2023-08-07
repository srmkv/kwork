<?php

namespace Database\Seeders;

use App\Models\Filter\FilterCategoryTag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FilterCategoryTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FilterCategoryTag::insert([

            [
                'title' => 'Направление',
                'slug' => \Str::slug('Направление'),
                'menu' => 1,
                'menu_title' => 'Направления',
            ],
            [
                'title' => 'Поднаправление',
                'slug' => \Str::slug('Поднаправление'),
                'menu' => 1,
                'menu_title' => 'Поднаправления',
            ],
            [
                'title' => 'Тип обучения',
                'slug' => \Str::slug('Тип обучения'),
                'menu' => 1,
                'menu_title' => 'Типы обучения',
            ],
            [
                'title' => 'Специальность',
                'slug' => \Str::slug('Специальность'),
                'menu' => 1,
                'menu_title' => 'Специальности',
            ],
        ]);
    }
}
