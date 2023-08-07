<?php

namespace Database\Seeders;

use App\Models\Course\Flow;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Flow::insert([
            [
                'start' => '2022-05-15',
                'end' => '2022-10-25',
                'study_form_id' => 1,
                'course_id' => 2,
            ],
            [
                'start' => '2022-08-15',
                'end' => '2022-12-25',
                'study_form_id' => 1,
                'course_id' => 1,
            ],
            [
                'start' => '2022-10-09',
                'end' => '2022-12-25',
                'study_form_id' => 1,
                'course_id' => 1,
            ],
            
            
        ]);
    }
}
