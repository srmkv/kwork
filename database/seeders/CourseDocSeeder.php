<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseDocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('course_docs')->insert([
            [
                'doc_id' => 1,
                'course_id' => 1,
            ],
            [
                'doc_id' => 2,
                'course_id' => 1,
            ],
            [
                'doc_id' => 3,
                'course_id' => 1,
            ],
            
            [
                'doc_id' => 1,
                'course_id' => 2,
            ],
            [
                'doc_id' => 1,
                'course_id' => 3,
            ],
            [
                'doc_id' => 2,
                'course_id' => 3,
            ],
            [
                'doc_id' => 2,
                'course_id' => 4,
            ],
            [
                'doc_id' => 2,
                'course_id' => 5,
            ],
        ]);
    }
}
