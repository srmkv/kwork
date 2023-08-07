<?php

namespace Database\Seeders;

use App\Models\Course\Speciality;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Speciality::insert([
            [
                'title' => 'Токарь-пекарь',
                'description' => 'description',
                'level_education_id' => '1'
            ],
            [
                'title' => 'Водолаз-гинеколог',
                'description' => 'description',
                'level_education_id' => '2'
            ],
            [
                'title' => 'Юморист-практолог',
                'description' => 'description',
                'level_education_id' => '3'
            ],
            
        ]);
    }
}
