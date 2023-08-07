<?php

namespace Database\Seeders;

use App\Models\Course\CourseState;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CourseState::insert([
            [
                'name' => 'Активен'
            ],
            [
                'name' => 'Неактивен'
            ],
            [
                'name' => 'На рассмотрении'
            ],
            [
                'name' => 'Новый'
            ],
            
        ]);
    }
}
