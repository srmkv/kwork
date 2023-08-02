<?php

namespace App\Console\Commands;

use App\Models\Course\Course;
use App\Services\CourseService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateMinMaxPricesCommand extends Command
{
    private $courseSrvice;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:updateMinMaxPrices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновляет min/max цены пакетов всех курсов';

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
            $prices = $this->courseSrvice->detectMinMaxPrices($course->flows()->with('packets')->get());
            $course->update([
                'min_price' => $prices->min(),
                'max_price' => $prices->max(),
            ]);
        }
        Log::info('Обновлены min/max price для ' . $courses->count() . ' курсов');
        return Command::SUCCESS;
    }
}
