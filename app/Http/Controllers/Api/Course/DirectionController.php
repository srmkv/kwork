<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\DirectionRequest;
use App\Http\Resources\DirectionResource;
use App\Models\Course\Direction;
use Illuminate\Http\Request;

class DirectionController extends Controller
{
    public function index(Direction $direction)
    {
        return response()->json(DirectionResource::collection($direction->directions), 201);
    }

    public function show(Direction $direction)
    {
        return response()->json(DirectionResource::make($direction), 200);
    }

    public function store(DirectionRequest $request)
    {
        return response()->json(DirectionResource::make(Direction::create($request->all())), 201);
    }

    public function update(DirectionRequest $request, Direction $direction)
    {
        return response()->json(DirectionResource::make($direction->update($request->all())), 200);
    }

    public function destroy(Direction $direction)
    {
        if($direction->delete()){
            return response()->json('direction deleted', 204);
        }
        return response()->json('direction not deleted', 204);
    }
}
