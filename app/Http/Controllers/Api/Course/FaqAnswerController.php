<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\FaqAnswerRequest;
use App\Http\Requests\FaqRequest;
use App\Http\Resources\FaqAnswerResource;
use App\Models\Course\Faq;
use App\Models\Course\FaqAnswer;
use Illuminate\Http\Request;

class FaqAnswerController extends Controller
{
    public function index()
    {
        return response()->json(FaqAnswerResource::collection(FaqAnswer::all()), 201);
    }

    public function show(FaqAnswer $faq)
    {
        return response()->json(FaqAnswerResource::make($faq), 200);
    }

    public function store(FaqAnswerRequest $request)
    {
        return response()->json(FaqAnswerResource::make(FaqAnswer::create($request->all())), 201);
    }

    public function update(FaqAnswerRequest $request, FaqAnswer $faq)
    {
        return response()->json(FaqAnswerResource::make($faq->update($request->all())), 200);
    }

    public function destroy(FaqAnswer $faq)
    {
        if($faq->delete()){
            return response()->json('faq answer deleted', 204);
        }
        return response()->json('faq answer not deleted', 204);
    }
}
