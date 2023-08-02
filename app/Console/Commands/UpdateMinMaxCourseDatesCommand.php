<?php

namespace App\Console\Commands;

use App\Models\Course\Course;
use App\Services\CourseService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateMinMaxCourseDatesCommand extends Command
{
    private $courseSrvice;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:updateMinMaxDates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновляет min/max даты пакетов всех курсов';

    public function __construct(CourseService $courseSrvice)
    {
        parent::__construct();
        $this->courseSrvice = $courseSrvice;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $courses = Course::all();
        foreach($courses as &$course){
            $this->courseSrvice->updateMinMaxDates($course);
        }
        Log::info('Обновлены min/max даты для ' . $courses->count() . ' курсов');
        return Command::SUCCESS;
    }
}
