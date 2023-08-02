<?php

namespace Database\Seeders;

use App\Models\DocEduDirection;
use Illuminate\Database\Seeder;

class DocEduDirectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DocEduDirection::insert([
            [
                'title' => 'Диплом о высшем медицинском образовании',
                'level_education_id' => 1,
            ],
            [
                'title' => 'Диплом о высшем спортивном образовании',
                'level_education_id' => 1,
            ],
            [
                'title' => 'Диплом о высшем техническом образовании',
                'level_education_id' => 1,
            ],
            [
                'title' => 'Диплом о высшем педагогическом образовании',
                'level_education_id' => 1,
            ],

            [
                'title' => 'Диплом о среднем медицинском образовании',
                'level_education_id' => 2,
            ],
            [
                'title' => 'Диплом о среднем спортивном образовании',
                'level_education_id' => 2,
            ],
            [
                'title' => 'Диплом о среднем техническом образовании',
                'level_education_id' => 2,
            ],
            [
                'title' => 'Диплом о среднем педагогическом образовании',
                'level_education_id' => 2,
            ],

            [
                'title' => 'Диплом о среднеспециальном медицинском образовании',
                'level_education_id' => 3,
            ],
            [
                'title' => 'Диплом о среднеспециальном спортивном образовании',
                'level_education_id' => 3,
            ],
            [
                'title' => 'Диплом о среднеспециальном техническом образовании',
                'level_education_id' => 3,
            ],
            [
                'title' => 'Диплом о среднеспециальном педагогическом образовании',
                'level_education_id' => 3,
            ],
            
        ]);
    }
}
