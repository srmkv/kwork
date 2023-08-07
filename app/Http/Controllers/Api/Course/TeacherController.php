<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherRequest;
use App\Http\Resources\Course\TeacherResource;
use App\Models\Course\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        return response()->json(TeacherResource::collection(Teacher::all()), 200);
    }

    public function show(Teacher $teacher)
    {
        return response()->json($teacher, 200);
    }

    public function store(TeacherRequest $request)
    {
        return response()->json(Teacher::create($request->all()), 201);
    }

    public function update(TeacherRequest $request, Teacher $teacher)
    {
        return response()->json($teacher->update($request->all()), 201);
    }

    public function destroy(TeacherRequest $request, Teacher $teacher)
    {
        $teacher->delete();
        return response()->json('delete success', 201);
    }
}
