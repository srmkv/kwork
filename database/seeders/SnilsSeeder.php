<?php

namespace Database\Seeders;

use App\Models\Snils;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SnilsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Snils::insert([
            [
                'number_snils' => 1234567898,
                'user_id' => 8,
                'doc_type_id' => 2,
            ],
            [
                'number_snils' => 987654321,
                'user_id' => 9,
                'doc_type_id' => 2,
            ],
            
        ]);
    }
}
