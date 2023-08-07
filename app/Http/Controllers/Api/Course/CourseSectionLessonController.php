<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseSectionLessonRequest;
use App\Http\Requests\LessonTeacherRequest;
use App\Models\Course\CourseSectionLesson;
use App\Models\Course\CourseSectionTheme;
use App\Services\CourseSectionLessonService;
use Illuminate\Http\Request;

class CourseSectionLessonController extends Controller
{
    private $courseSectionLessonService;

    public function __construct(CourseSectionLessonService $courseSectionLessonService)
    {
        $this->courseSectionLessonService = $courseSectionLessonService;
    }

    public function delete(CourseSectionLessonRequest $request)
    {
        $lesson = CourseSectionLesson::findOrFail($request->id);
        $result = $this->courseSectionLessonService->delete($lesson);
        if($result !== true){
            return response()->json($result, 401);
        }
        return response()->json('Урок с id ' . $request->id . ' удалён', 200);
    }

    public function teacherDelete(LessonTeacherRequest $reqqquest)
    {
        
    }
}
