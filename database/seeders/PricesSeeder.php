<?php

namespace Database\Seeders;

use App\Models\Course\Price;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {//1,,25000.00,5000.00,20000.00,8
        Price::insert([
            [
                'old_price' => 35000.00,
                'new_price' => 25000.00,
                'instalment_price' => 5000.00,
                'default_price' => 20000.00,
                'discount' => 8,
            ],
            [
                'old_price' => 40000.00,
                'new_price' => 35000.00,
                'instalment_price' => 7000.00,
                'default_price' => 21000.00,
                'discount' => 6,
            ],
            
        ]);
    }
}
