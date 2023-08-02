<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Course\Course;
use App\Models\Course\NeededPersonalDoc;

use App\Http\Resources\Course\PersonalDocsCourseResource;
use App\Http\Resources\Course\PersonalDocResource;

class PersonalDocController extends Controller
{
    

    public function createPersonalDocCourse(Request $request)
    {
        $personal_doc_course = new NeededPersonalDoc;
        $personal_doc_course->course_id = $request->course_id;
        $personal_doc_course->save();
        return $personal_doc_course;
    }

    public function editPersonalDocCourse(Request $request)
    {   
        $personal_doc_course = NeededPersonalDoc::find($request->document_id);
        $personal_doc_course->description = $request->description;
        $personal_doc_course->title = $request->title;
        $personal_doc_course->required_docs = $request->personal_checked_docs;
        $personal_doc_course->other_type_docs = $request->other_type_docs;
        $personal_doc_course->save();
        return PersonalDocResource::make($personal_doc_course);

    }

    public function getPersonalDocsCourse(Request $request, $course_id)
    {
        $course = Course::find($course_id);
        $course_id = $course->id;
        return PersonalDocsCourseResource::collection($course->neededPersonalDocs, $course_id);
    }


    public function deletePersonalDocCourse(Request $request, $document_id)
    {
        $personal_doc_course = NeededPersonalDoc::find($request->document_id);
        $personal_doc_course->delete();

        return response()->json([
            'message' => 'успешно удалили документ..'
        ], 201);
    }

    public function personaDocsAllTypes(Request $request)
    {
        return collect(\DB::table('docs')->get());
        
    }

}

