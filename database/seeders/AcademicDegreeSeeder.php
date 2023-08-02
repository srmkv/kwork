<?php

namespace Database\Seeders;

use App\Models\Course\AcademicDegree;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcademicDegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AcademicDegree::insert([
            [
                'title' => 'Доктор наук'
            ],
            [
                'title' => 'Кандидат в доктора наук'
            ],
            [
                'title' => 'Младший научный сотрудник'
            ],
            
        ]);
    }
}
