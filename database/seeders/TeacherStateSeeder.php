<?php

namespace Database\Seeders;

use App\Models\Course\TeacherState;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TeacherState::insert([
            [
                'name' => 'Преподаватель'
            ],
            [
                'name' => 'Куратор'
            ],
            [
                'name' => 'Консультант'
            ],
            
        ]);
    }
}
