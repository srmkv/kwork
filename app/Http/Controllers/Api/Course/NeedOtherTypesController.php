<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Course\Course;
use App\Models\Course\NeedOtherType;

use App\Http\Resources\Course\OtherTypesCourseResource;
use App\Http\Resources\Course\OtherTypeResource;


class NeedOtherTypesController extends Controller
{
    public function createNeedOtherType(Request $request)
    {
        $type = new NeedOtherType;
        $type->course_id = $request->course_id;
        $type->save();
        return $type;
    }


    public function editNeedOtherType(Request $request)
    {
        $doc_type = NeedOtherType::find($request->document_id);
        $doc_type->title = $request->title;
        $doc_type->description = $request->description;
        $doc_type->required_types = $request->other_types_checked;
        $doc_type->save();
        // return $doc_type;

        return OtherTypeResource::make($doc_type);
    }

    public function getOtherTypesCourse(Request $request, $course_id)
    {

        return OtherTypesCourseResource::collection(Course::find($course_id)->needOtherTypes);
    }

    public function deleteOtherTypesCourse(Request $request, $document_id)
    {
        $doc_type = NeedOtherType::find($document_id);
        $doc_type->delete();
        return response()->json([
            'message' => 'дополнительный документ удален..'
        ], 201);
    }
}
