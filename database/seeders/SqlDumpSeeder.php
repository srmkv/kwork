<?php

namespace Database\Seeders;

use App\Models\Course\Filter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SqlDumpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info('Запуск SqlDumpSeeder');
        $locale = 'en_US.utf-8';
        setlocale(LC_ALL, $locale);
        putenv('LC_ALL='.$locale);
        
        $connection_string = "mysql -h ".env('DB_HOST')." -u ".env('DB_USERNAME').
            " -p '".env('DB_PASSWORD')."' ".
            env('DB_DATABASE')." < ";
            Log::info('Сформирован connection для seed');

        foreach (Storage::disk('dump_sql_files')->files() as $file) {
            if ($file === 'dev.qualifiterra.ru-DATA.sql' || $file === 'users+profiles_individuals.sql') {
                Log::info('Найден файл dev.qualifiterra.ru-DATA.sql');
                $path = 'storage/dump_sql_files/' . $file;
                $result = exec('LANG=en_US.utf-8; ' . $connection_string . base_path() . '/' . $path);
                Log::info($result);
                return;
            }
        }
        Log::info('НЕ найден файл dev.qualifiterra.ru-DATA.sql');
    }
}
