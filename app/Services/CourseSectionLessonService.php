<?php

namespace App\Services;

use App\Models\Course\CourseSectionLesson;
use Exception;

class CourseSectionLessonService
{
    public function delete(CourseSectionLesson $lesson)
    {
        try {
            $lesson->teachers()->detach();
            $lesson->themes()->delete();
            $lesson->delete();
            return true;
        } catch (Exception $e) {
            return $e->getMessage() . ' # ' . $e->getFile() . ' # ' . $e->getLine();
        }
    }


    public static function getLocationsInFlow($flow_id)
    {   
        $locations = [];
        $sections = \DB::table('course_sections')->where('flow_id', $flow_id)->get();

        foreach ($sections as $section) {
            
            $lessons = \DB::table('course_section_lessons')->where('course_section_id', $section->id)->get();

            foreach ($lessons as $lesson) {
                if($lesson->address != null) {
                     array_push($locations, $lesson->address );
                }
            }

        }

        return $locations;

    }
}