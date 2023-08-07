<?php

namespace Database\Seeders;

use App\Models\Passport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PasportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Passport::insert([
            [
                'country_id' => 1,
                'user_id' => 8,
                'doc_type_id' => 1,
                'last_name' => 'Зорге',
                'first_name' => 'Рихард',
                'middle_name' => 'Иванович',
                'date_of_birth' => '1923-05-07',
                'serial_number' => '8945 124578',
                'issued_by_whom' => 'МВД ССР Московское ГУ',
                'date_issue' => '1931-12-01',
                'subdivision_code' => '1945',
            ],
            [
                'country_id' => 1,
                'user_id' => 9,
                'doc_type_id' => 1,
                'last_name' => 'Штирлиц',
                'first_name' => 'Иван',
                'middle_name' => 'Петрович',
                'date_of_birth' => '1922-07-12',
                'serial_number' => '2547 325487',
                'issued_by_whom' => 'МВД ССР Ленинградское ГУ',
                'date_issue' => '1930-05-24',
                'subdivision_code' => '1944',
            ],
            

        ]);
    }
}
