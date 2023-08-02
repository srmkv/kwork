<?php

namespace Database\Seeders;

use App\Models\Course\CourseRating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CourseRating::insert([
            [
                'user_id' => 8,
                'course_id' => 1,
                'rating' => 3,
            ],
            [
                'user_id' => 9,
                'course_id' => 1,
                'rating' => 4,
            ],
            [
                'user_id' => 79,
                'course_id' => 1,
                'rating' => 5,
            ],
            
        ]);
    }
}
