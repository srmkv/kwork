<?php

namespace Database\Seeders;

use App\Models\Course\FlowType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlowTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FlowType::insert([
            [
                'title' => 'Группами',
                'slug' => \Str::slug('Группами')
            ],
            [
                'title' => 'Начало сразу после оплаты',
                'slug' => \Str::slug('Начало сразу после оплаты')
            ],
            
        ]);
    }
}
