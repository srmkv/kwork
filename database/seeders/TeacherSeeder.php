<?php

namespace Database\Seeders;

use App\Models\Course\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Teacher::insert([
            [
                'name' => 'Вася Вялый',
                'degree_id' => 1,
                'description' => 'description',
            ],
            [
                'name' => 'Жора Ваточник',
                'degree_id' => 2,
                'description' => 'description',
            ],
            [
                'name' => 'Петя Кривой',
                'degree_id' => 3,
                'description' => 'description',
            ],
            
            
        ]);
    }
}
