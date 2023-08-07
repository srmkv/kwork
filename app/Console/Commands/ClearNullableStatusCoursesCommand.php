<?php

namespace App\Console\Commands;

use App\Models\Course\Course;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Exception;

class ClearNullableStatusCoursesCommand extends Command
{
    private $courseSrvice;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:clearNullableStatusCourses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Удаляет курсы со статусом NULL';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try{
            $courses = Course::nullable()->delete();
            Log::info('Удаление нулевых курсов', [$courses]);
        }catch(Exception $e){
            Log::error($e->getFile() . ' # ' . $e->getLine() . ' # ' . $e->getMessage());
        }
        return Command::SUCCESS;
    }
}
