<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Resources\LevelEducationResource;
use App\Models\Course\LevelEducation;
use Illuminate\Http\Request;

class LevelEducationController extends Controller
{
    public function index()
    {
        return LevelEducationResource::collection(LevelEducation::all());
    }
}
