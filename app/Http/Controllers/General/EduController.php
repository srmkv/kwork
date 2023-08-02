<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Course\Direction;
use App\Models\Course\StudyForm;
use App\Models\Course\NeededSpeciality;
use App\Models\Course\Course;
use App\Models\SpecializedSecondaryEdu\SpecializedSecondarySpeciality;
use App\Models\SpecializedSecondaryEdu\SpecializedSecondaryDirection;
use App\Models\HigherEdu\HigherResidencyDirection;
use App\Models\HigherEdu\HigherResidencySpeciality;
use App\Models\HigherEdu\HigherSpecialitetSpeciality;
use App\Models\HigherEdu\HigherSpecialitetDirection;
use App\Models\HigherEdu\HigherPostgraduateSpeciality;
use App\Models\HigherEdu\HigherPostgraduateDirection;
use App\Models\HigherEdu\HigherAssistantIntershipSpeciality;
use App\Models\HigherEdu\HigherAssistantIntershipDirection;
use App\Models\HigherEdu\HigherBackelorSpeciality;
use App\Models\HigherEdu\HigherBackelorDirection;
use App\Models\HigherEdu\HigherMasterSpeciality;
use App\Models\HigherEdu\HigherMasterDirection;

use App\Http\Resources\Course\GetStudyDocsCourse;
use App\Http\Resources\Course\GetStudyMoreInformationDocsCourse;
use App\Http\Resources\Course\MoreInformationDocEduResource;
use App\Http\Resources\Course\PersonalDocsMoreInformationCourseResource;
use App\Http\Resources\Course\OtherTypesMoreCourseResource;

use App\Services\Order\MoreInformationDocumentService;


class EduController extends Controller
{
    


    public function getDirections(Request $request)
    {
        $directions = Direction::all();

        return collect($directions);
    }



    public function getStudyForms(Request $request)
    {
        $forms = StudyForm::all();

        return collect($forms);
    }



    public function getHigherSpecific(Request $request)
    {
        $specs = collect();
        $directions = collect();
        $filter = collect();

        foreach ($request->type_edu as $indexType => $type) {
            $specs_db = null;

            switch ($type['name']) {
                case 'specialitet':
                    $dirs = $type['directions'];
                    if(count($dirs) > 0) {
                        $directions['specialitet'] = HigherSpecialitetDirection::with('specialities')->find($dirs);
                        continue 2;
                    } 
                    $specs_db = HigherSpecialitetSpeciality::all();
                    $specs['specialitet'] = $specs_db;  
                    break;
                case 'master':
                    $dirs = $type['directions'];
                    if(count($dirs) > 0) {
                        $directions['master'] = HigherMasterDirection::with('specialities')->find($dirs);
                        continue 2;
                    }
                    $specs_db = HigherMasterSpeciality::all();
                    $specs['master'] = $specs_db;
                    break;
                case 'residency':
                    $dirs = $type['directions'];
                    if(count($dirs) > 0) {
                        $directions['residency'] = HigherResidencyDirection::with('specialities')->find($dirs);
                        continue 2;
                    }
                    $specs_db = HigherResidencySpeciality::all();
                    $specs['residency'] = $specs_db;
                    break;
                case 'postgraduate':
                    $dirs = $type['directions'];
                    if(count($dirs) > 0) {
                        $directions['postgraduate'] = HigherPostgraduateDirection::with('specialities')->find($dirs);
                        continue 2;
                    }
                    $specs_db = HigherPostgraduateSpeciality::all();
                    $specs['postgraduate'] = $specs_db;
                    break;
                case 'assistant_intership':
                    $dirs = $type['directions'];
                    if(count($dirs) > 0) {
                        $directions['assistant_intership'] = HigherAssistantIntershipDirection::with('specialities')->find($dirs);
                        continue 2;
                    }
                    $specs_db = HigherAssistantIntershipSpeciality::all();
                    $specs['assistant_intership'] = $specs_db;
                    break;
                case 'backelor':
                    $dirs = $type['directions'];
                    if(count($dirs) > 0) {
                        $directions['backelor'] = HigherBackelorDirection::with('specialities')->find($dirs);
                        continue 2;
                    }
                    $specs_db = HigherBackelorSpeciality::all();
                    $specs['backelor'] = $specs_db;
                    break;

                case 'specialized_secondary':
                    $dirs = $type['directions'];
                    if(count($dirs) > 0) {
                        $directions['specialized_secondary'] = SpecializedSecondaryDirection::with('specialities')->find($dirs);
                        continue 2;
                    }
                    $specs_db = SpecializedSecondarySpeciality::all();
                    $specs['specialized_secondary'] = $specs_db;
                    break;

                default:
                    continue 2;
                    break;
            }

        }

        $filter['filtered_directions'] = $directions;
        $filter['filtered_specialities'] = $specs;

        return $filter;

    }

    public function attachStudyDocsCourse(Request $request)
    {
        $specs = collect();
        $directions = collect();
        // $main_doc = collect();

        $course_id = $request->course_id;
        $course  = Course::find($course_id);

        foreach ($request->type_edu as $indexType => $type) {

            $temp_dirs = $type['directions'];
            $temp_specs = $type['specialities'];

            if(count($temp_dirs) > 0 || count($temp_specs) > 0)    {
                $specs->push($type);
                continue;
                break;
            }
        }

        $other_type_docs = collect($request->other_type_docs);

        $need = $course->neededSpecialities()->updateOrCreate([
            'course_id' => $course_id,
            'id' => $request->document_id
        ], [
            
            'needed_edu_docs' => $specs,
            'other_type_docs' => $other_type_docs

            
        ]);

        $need = NeededSpeciality::find($need->id);

        $edu_doc['replacing_docs'] = $need->append('needed_edu_docs')->needed_edu_docs;
        $edu_doc['other_type_docs'] = $need->append('other_type_docs')->other_type_docs;

        return $edu_doc;

    }



    public function getCourseStudyDocs(Request $request, $course_id)
    {
        $course = Course::find($course_id);
        $needs = NeededSpeciality::where('course_id', $course->id)->get();
        return GetStudyDocsCourse::collection($needs);

    }


    //фильтруем направления
    public function getDirectionsSpecific(Request $request)
    {
        $directions = $request->directions;
        $dirs = collect();

        foreach ($directions as $index => $direction) {
            
            switch ($direction) {
                case 'specialitet':
                    
                    $dirs['specialitet'] =  HigherSpecialitetDirection::all();
                    continue 2;
                    break;
                
                case 'backelor':
                    $dirs['backelor'] =  HigherBackelorDirection::all();
                    continue 2;
                    break;

                case 'master':
                    $dirs['master'] =  HigherMasterDirection::all();
                    continue 2;
                    break;

                case 'residency':
                    $dirs['residency'] =  HigherResidencyDirection::all();
                    continue 2;
                    break;

                case 'postgraduate':
                    $dirs['postgraduate'] =  HigherPostgraduateDirection::all();
                    continue 2;
                    break;

                case 'assistant_intership':
                    $dirs['assistant_intership'] =  HigherAssistantIntershipDirection::all();
                    continue 2;
                    break;

                case 'specialized_secondary':
                    $dirs['specialized_secondary'] =  SpecializedSecondaryDirection::all();
                    continue 2;
                    break;


                default:
                    continue 2;
                    break;
            }
        }


        return $dirs;
    }


    public function createEduDocCourse(Request $request)
    {
        $edu_doc_course = new NeededSpeciality;
        $edu_doc_course->course_id = $request->course_id;
        $edu_doc_course->save();

        return $edu_doc_course;
    }



    public function editEduDocCourse(Request $request)
    {
        $need = NeededSpeciality::find($request->document_id);
        $need->description = $request->description;
        $need->title = $request->title;
        $need->save();
        return $need;
    }


    public function deleteEduDocCourse(Request $request)
    {
        $need = NeededSpeciality::find($request->document_id);
        $need->delete();
        return response()->json([
            'message' => 'успешно удалили документ..'
        ], 201);
    }



    // ВСЕ НИЖЕ ДЛЯ ОРДЕРА

    // EDU DOC (ID)
    public function getDocumentEdu(Request $request, $document_id)
    {   
        $need = NeededSpeciality::find($document_id);
        $edu_doc['array_documents'] = MoreInformationDocEduResource::collection($need->append('needed_edu_docs')->needed_edu_docs);
        $edu_doc['other_type_docs'] = MoreInformationDocumentService::otherTypeDocs($need->append('other_type_docs')->other_type_docs);
        $edu_doc['title'] = $need->title;
        $edu_doc['course_id'] = $need->course_id;
        $edu_doc['id'] = $need->id;
        return $edu_doc;

    }

    // EDU DOC (ВСЁ В КУРСЕ)
    public function getDocumenstEduIntoCourse(Request $request, $course_id)
    {   
        $course = Course::find($course_id);
        $needs = NeededSpeciality::where('course_id', $course->id)->get();
        return GetStudyMoreInformationDocsCourse::collection($needs);

    }



    public function getAllNeedDocuments(Request $request, $course_id)
    {
        $course = Course::find($course_id);
        $needs = NeededSpeciality::where('course_id', $course->id)->get();
        $NeedDocs["education"] = GetStudyMoreInformationDocsCourse::collection($needs);
        $NeedDocs["personal"] = PersonalDocsMoreInformationCourseResource::collection($course->neededPersonalDocs);
        $NeedDocs["other"] = OtherTypesMoreCourseResource::collection(Course::find($course_id)->needOtherTypes);


        return $NeedDocs;



    }
    

}

