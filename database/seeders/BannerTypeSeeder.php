<?php

namespace Database\Seeders;

use App\Models\Course\BannerType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BannerType::insert([
            [
                'name' => 'Картинка'
            ],
            [
                'name' => 'Градиент'
            ],
            [
                'name' => 'Анимированный градиент'
            ],
        ]);
    }
}
