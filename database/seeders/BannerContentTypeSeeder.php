<?php

namespace Database\Seeders;

use App\Models\Course\BannerContentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerContentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BannerContentType::insert([
            [
                'name' => 'Фото'
            ],
            [
                'name' => 'Видео'
            ],
            
        ]);
    }
}
