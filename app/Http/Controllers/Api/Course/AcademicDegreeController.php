<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcademicDegreeRequest;
use App\Http\Resources\Course\AcademicDegreeResource;
use App\Models\Course\AcademicDegree;
use Illuminate\Http\Request;

class AcademicDegreeController extends Controller
{
    public function index()
    {
        return response()->json(AcademicDegreeResource::collection(AcademicDegree::all()), 201);
    }

    public function show(AcademicDegree $AcademicDegree)
    {
        return response()->json(AcademicDegreeResource::make($AcademicDegree), 200);
    }

    public function store(AcademicDegreeRequest $request)
    {
        return response()->json(AcademicDegreeResource::make(AcademicDegree::create($request->all())), 201);
    }

    public function update(AcademicDegreeRequest $request, AcademicDegree $academicDegree)
    {
        return response()->json(AcademicDegreeResource::make($academicDegree->update($request->all())), 200);
    }

    public function destroy(AcademicDegree $academicDegree)
    {
        if($academicDegree->delete()){
            return response()->json('AcademicDegree deleted', 204);
        }
        return response()->json('AcademicDegree not deleted', 204);
    }
}
