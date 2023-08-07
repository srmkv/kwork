<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseEduDocsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('course_edu_docs')->insert([
            [
                'course_id' => 1,
                'doc_edu_direction_id' => 1,
            ],
            [
                'course_id' => 1,
                'doc_edu_direction_id' => 7,
            ],
            
        ]);
    }
}
