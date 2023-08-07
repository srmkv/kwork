<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\FaqQuestionRequest;
use App\Http\Resources\FaqQuestionResource;
use App\Models\Course\FaqQuestion;
use Illuminate\Http\Request;

class FaqQuestionController extends Controller
{
    public function index()
    {
        return response()->json(FaqQuestionResource::collection(FaqQuestion::all()), 201);
    }

    public function show(FaqQuestion $faq)
    {
        return response()->json(FaqQuestionResource::make($faq), 200);
    }

    public function store(FaqQuestionRequest $request)
    {
        return response()->json(FaqQuestionResource::make(FaqQuestion::create($request->all())), 201);
    }

    public function update(FaqQuestionRequest $request, FaqQuestion $faq)
    {
        return response()->json(FaqQuestionResource::make($faq->update($request->all())), 200);
    }

    public function destroy(FaqQuestion $faq)
    {
        if($faq->delete()){
            return response()->json('faq question deleted', 204);
        }
        return response()->json('faq question not deleted', 204);
    }
}
