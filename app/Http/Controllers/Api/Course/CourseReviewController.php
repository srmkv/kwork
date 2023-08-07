<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseReview\CourseReviewDeleteRequest;
use App\Http\Requests\CourseReview\CourseReviewRequest;
use App\Http\Requests\CourseReview\CourseReviewStoreRequest;
use App\Http\Requests\CourseReview\CourseReviewUpdateRequest;
use App\Http\Resources\CourseReviewResource;
use App\Models\Course\Course;
use App\Models\Course\CourseReview;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseReviewController extends Controller
{
    private $courseSrvice;

    public function __construct(CourseService $courseSrvice)
    {
        $this->courseSrvice = $courseSrvice;
    }

    public function index(CourseReviewRequest $request)
    {
        $course = Course::findOrFail($request->course_id);
        return response()->json(CourseReviewResource::collection($course->reviews), 200);
    }

    public function store(CourseReviewStoreRequest $request)
    {
        $reviews = $this->courseSrvice->addReview($request);
        return response()->json(CourseReviewResource::collection($reviews), 201);
    }

    public function update(CourseReviewUpdateRequest $request)
    {
        $courseReview = CourseReview::findOrFail($request->id);
        $courseReview->update($request->all());
        return response()->json(CourseReviewResource::make($courseReview), 201);
    }

    public function destroy(CourseReviewDeleteRequest $request)
    {
        $courseReview = CourseReview::findOrFail($request->id);
        if($courseReview->remove()){
            return response()->json('отзыв удалён', 201);
        }
        return response()->json('отзыв не удалён', 201);
    }

    public function publish(CourseReviewDeleteRequest $request)
    {
        $courseReview = CourseReview::findOrFail($request->id);
        $courseReview->update([
            'is_published' => (int)$request->publish 
        ]);
        if((int)$request->publish){
            return response()->json('Отзыв опубликован', 201);
        }
        return response()->json('Отзыв снят с публикации', 201);
    }

    public function list(Request $request)
    {
        return response()->json(CourseReviewResource::collection(CourseReview::withoutParent()->get()), 200);
    }

    
}
