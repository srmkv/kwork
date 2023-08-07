<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\FaqRequest;
use App\Http\Resources\FaqResource;
use App\Models\Course\Course;
use App\Models\Course\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Course $course)
    {
        return response()->json(FaqResource::collection($course->faqs), 201);
    }

    public function show(Faq $faq)
    {
        return response()->json(FaqResource::make($faq), 200);
    }

    public function store(FaqRequest $request)
    {
        return response()->json(FaqResource::make(Faq::create($request->all())), 201);
    }

    public function update(FaqRequest $request, Faq $faq)
    {
        return response()->json(FaqResource::make($faq->update($request->all())), 200);
    }

    public function destroy(Faq $faq)
    {
        if($faq->delete()){
            return response()->json('faq deleted', 204);
        }
        return response()->json('faq not deleted', 204);
    }
}
