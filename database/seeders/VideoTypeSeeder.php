<?php

namespace Database\Seeders;

use App\Models\Course\VideoType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideoTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VideoType::insert([
            [
                'name' => 'Ссылка'
            ],
            [
                'name' => 'Видео'
            ],
            
        ]);
    }
}
