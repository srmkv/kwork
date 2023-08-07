<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseLevelEducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('course_level_education')->insert([
            [
                'level_education_id' => 1,
                'course_id' => 1,
            ],
            [
                'level_education_id' => 2,
                'course_id' => 1,
            ],
            
        ]);
    }
}
