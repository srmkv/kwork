<?php

namespace App\Services;

use App\Models\Course\CourseSection;
use App\Models\Course\Flow;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class CourseSectionService
{
    private $courseSectionLessonService;

    public function __construct(CourseSectionLessonService $courseSectionLessonService)
    {
        $this->courseSectionLessonService = $courseSectionLessonService;
    }

    public function delete(CourseSection $section)
    {
        try{
            foreach($section->lessons as $lesson){
                $deleted = $this->courseSectionLessonService->delete($lesson);
                if($deleted !== true){
                    return $deleted;
                }
            }
            $section->delete();
            return true;
        }catch(Exception $e){
            return $e->getMessage() . ' # ' . $e->getFile() . ' # ' . $e->getLine();
        }
    }
}