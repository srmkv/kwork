<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseSectionThemeRequest;
use App\Http\Resources\CourseSectionThemeResource;
use App\Models\Course\CourseSectionTheme;
use Illuminate\Http\Request;

class CourseSectionThemeController extends Controller
{
    public function delete(CourseSectionThemeRequest $request)
    {
        CourseSectionTheme::findOrFail($request->id)->delete();
        return response()->json('theme deleted', 200);
    }
}
