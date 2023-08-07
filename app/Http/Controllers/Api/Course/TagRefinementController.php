<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRefinementDeleteRequest;
use App\Http\Requests\TagRefinementRequest;
use App\Http\Resources\Course\TagRefinementResource;
use App\Models\Course\TagRefinement;
use Illuminate\Http\Request;

class TagRefinementController extends Controller
{
    public function index()
    {
        return response()->json(TagRefinement::all(), 200);
    }

    public function show(Request $request)
    {
        return response()->json(TagRefinementResource::make(TagRefinement::find($request->id)), 200);
    }

    public function store(TagRefinementRequest $request)
    {
        return response()->json(TagRefinementResource::make(TagRefinement::create($request->all())), 201);
    }

    public function update(TagRefinementRequest $request)
    {
        return response()->json(TagRefinementResource::collection(TagRefinement::find($request->id)->update($request->all())), 201);
    }

    public function destroy(TagRefinementDeleteRequest $request)
    {
        TagRefinement::find($request->id)->delete();
        return response()->json(TagRefinementResource::collection(TagRefinement::all()), 201);
    }
}
