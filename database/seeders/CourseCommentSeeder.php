<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('course_comments')->insert([
            [
                'course_id' => 1,
                'comment' => 'Отличный курс',
            ],
            [
                'course_id' => 1,
                'comment' => 'Чот дороговато...',
            ],
            
        ]);
    }
}
