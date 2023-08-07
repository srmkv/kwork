<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseSectionRequest;
use App\Models\Course\CourseSection;
use App\Services\CourseSectionService;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Request;

class CourseSectionController extends Controller
{
    use ApiResponser;

    private $courseSectionService;

    public function __construct(CourseSectionService $courseSectionService)
    {
        $this->courseSectionService = $courseSectionService;
    }
    
    public function delete(CourseSectionRequest $request)
    {
        $section = CourseSection::findOrFail($request->id);
        $result = $this->courseSectionService->delete($section);
        if($result !== true){
            return response()->json($result, 401);
        }
        return response()->json('Раздел потока удалён', 200);
    }
}
