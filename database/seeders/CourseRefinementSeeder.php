<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseRefinementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('course_refinements')->insert([
            [
                'tag_refinement_id' => 1,
                'course_id' => 1,
            ],
            [
                'tag_refinement_id' => 2,
                'course_id' => 1,
            ],
            [
                'tag_refinement_id' => 3,
                'course_id' => 1,
            ],
            
            
        ]);
    }
}
