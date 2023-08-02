<?php

namespace App\Console\Commands;

use App\Models\Course\Course;
use App\Services\CatalogService;
use App\Services\CourseService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateCatalogBitMapCommand extends Command
{
    private $courseSrvice;
    private $catalogSrvice;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:createBitMapCatalog {c?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание bitmap в Redis для фильтров и каталога';

    public function __construct(CourseService $courseSrvice, CatalogService $catalogSrvice)
    {
        parent::__construct();
        $this->courseSrvice = $courseSrvice;
        $this->catalogSrvice = $catalogSrvice;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if($this->argument('c')){
            $this->catalogSrvice->clearCache();
            $this->catalogSrvice->clearRedis();
        }
        try{
            $courseIdsExecute = collect();
            Course::all()->each(function($course)use($courseIdsExecute){
                if($this->courseSrvice->writeBitMapToRedis($course)){
                    $courseIdsExecute->push($course->id);
                }
            });
            if($courseIdsExecute->count()){
                Log::info('В Redis сформирован bitmap для кусов с id: ', $courseIdsExecute->toArray());
                return true;
            }
            Log::warning('В Redis не сформирован bitmap ни для одного курса');
        }catch(Exception $e){
            Log::error($e->getFile() . ' # ' . $e->getLine() . ' # ' . $e->getMessage());
            return false;
        }
    }
}
