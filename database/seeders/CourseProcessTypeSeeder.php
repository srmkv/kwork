<?php

namespace Database\Seeders;

use App\Models\Course\CourseProcessType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseProcessTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CourseProcessType::insert([
            [
                'name' => 'Гость',
            ],
            [
                'name' => 'Зачислен на курс',
            ],
            [
                'name' => 'В процессе обучения',
            ],
            [
                'name' => 'Обучение завершено',
            ],
            [
                'name' => 'Отчислен с курса',
            ],
        ]);
    }
}
