<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CourseEduOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('course_edu_organizations')->insert([
            [
                'course_id' => 1,
                'edu_organization_id' => 1,
            ],
            [
                'course_id' => 2,
                'edu_organization_id' => 2,
            ],
            
        ]);
    }
}
