<?php

namespace Database\Seeders;

use App\Models\Course\Direction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DirectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Direction::insert([
            [
                'title' => 'Web-разработка'
            ],
            [
                'title' => 'Гинекология'
            ],
            [
                'title' => 'Строительство'
            ],
            
        ]);
    }
}
