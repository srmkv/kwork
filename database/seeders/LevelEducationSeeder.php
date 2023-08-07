<?php

namespace Database\Seeders;

use App\Models\Course\LevelEducation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelEducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LevelEducation::insert([
            [
                'title' => 'Высшее образование',
                'slug' => \Str::slug('Высшее образование')
            ],
            [
                'title' => 'Среднее образование',
                'slug' => \Str::slug('Среднее образование')
            ],
            [
                'title' => 'Среднеспециальное образование',
                'slug' => \Str::slug('Среднеспециальное образование')
            ],
        ]);
    }
}
