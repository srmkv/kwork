<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Models\Course\Profession;


class VacantPlaceCourseController extends Controller
{
    public function editPlaceForEduProgram(Request $request)
    {
        $course = Course::find($request->course_id);
        $course->vacantPlaceEduProgram()->updateOrCreate(
            [
                'course_id' => $request->course_id
            ],

            [
                'budget_allocations_federal_budget' => $request->budget_allocations_federal_budget ?? 0,
                'budget_allocations_subject_rf_budget' => $request->budget_allocations_subject_rf_budget ?? 0,
                'budget_allocations_local_budget' => $request->budget_allocations_local_budget ?? 0,
                'budget_allocations_individ_business_budget' => $request->budget_allocations_individ_business_budget ?? 0

            ],
        );
        return collect($course->vacantPlaceEduProgram)->except(['id','course_id']);
    }

    public function editPlaceForSpeciality(Request $request)
    {
        $course = Course::find($request->course_id);
        $course->vacantPlaceSpeciality()->updateOrCreate(
            [
                'course_id' => $request->course_id
            ],
            [
                'budget_allocations_federal_budget' => $request->budget_allocations_federal_budget ?? 0,
                'budget_allocations_subject_rf_budget' => $request->budget_allocations_subject_rf_budget ?? 0,
                'budget_allocations_local_budget' => $request->budget_allocations_local_budget ?? 0,
                'budget_allocations_individ_business_budget' => $request->budget_allocations_individ_business_budget ?? 0
            ],
        );
        return collect($course->vacantPlaceSpeciality)->except(['id','course_id']);
    }

    public function editPlaceForDirection(Request $request)
    {
        $course = Course::find($request->course_id);
        $course->vacantPlaceDirection()->updateOrCreate(
            [
                'course_id' => $request->course_id
            ],
            [
                'budget_allocations_federal_budget' => $request->budget_allocations_federal_budget ?? 0,
                'budget_allocations_subject_rf_budget' => $request->budget_allocations_subject_rf_budget ?? 0,
                'budget_allocations_local_budget' => $request->budget_allocations_local_budget ?? 0,
                'budget_allocations_individ_business_budget' => $request->budget_allocations_individ_business_budget ?? 0
            ],
        );
        return collect($course->vacantPlaceDirection)->except(['id','course_id']);
    }

    public function editPlaceForProfession(Request $request)
    {
        $course = Course::find($request->course_id);
        $course->vacantPlaceProfession()->updateOrCreate(
            [
                'course_id' => $request->course_id
            ],
            [   'profession_id' => $request->profession_id,
                'budget_allocations_federal_budget' => $request->budget_allocations_federal_budget ?? 0,
                'budget_allocations_subject_rf_budget' => $request->budget_allocations_subject_rf_budget ?? 0,
                'budget_allocations_local_budget' => $request->budget_allocations_local_budget ?? 0,
                'budget_allocations_individ_business_budget' => $request->budget_allocations_individ_business_budget ?? 0
            ],
        );
        return collect($course->vacantPlaceProfession)->except(['id','course_id']);
    }

    public function createProfession(Request $request)
    {
        $prof = new Profession;
        $prof->title = $request->title;
        $prof->save();

        return $prof;
    }


    public function getProfessions(Request $request)
    {
        return Profession::all();
    }


}
