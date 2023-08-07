<?php

namespace Database\Seeders;

use App\Models\Course\PayMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PayMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PayMethod::insert([
            [
                'name' => 'Безналичная оплата',
                'slug' => 'cashless_pay'
            ],

            [
                'name' => 'Банковский перевод',
                'slug' => 'banking'
            ],
        ]);
    }
}
