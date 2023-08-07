<?php

namespace Database\Seeders;

use App\Models\Course\TagRefinement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagRefinementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TagRefinement::insert([
            [
                'title' => 'Супер круто', 
                'slug' => \Str::slug('Супер круто'), 
            ],
            [
                'title' => 'В системе НМО', 
                'slug' => \Str::slug('В системе НМО'), 
            ],
            [
                'title' => 'Практически даром',
                'slug' => \Str::slug('Практически даром'),
            ],
            
            
        ]);
    }
}
