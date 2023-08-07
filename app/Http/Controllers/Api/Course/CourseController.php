<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddPreviewRequest;
use App\Http\Requests\CourseBySlugRequest;
use App\Http\Requests\CourseDocTakeStoreRequest;
use App\Http\Requests\CourseProcessRequest;
use App\Http\Requests\CourseRequest;
use App\Http\Requests\CourseStoreRequest;
use App\Http\Requests\CourseVideoDeleteRequest;
use App\Http\Requests\CourseVideoStoreRequest;
use App\Http\Requests\CRUDCourseDocImagesRequest;
use App\Http\Requests\DeleteRequiredDocumentRequest;
use App\Http\Requests\DeleteRequiredEduDocumentRequest;
use App\Http\Requests\DocTakeDeleteRequest;
use App\Http\Requests\DocTakeImageDeleteRequest;
use App\Http\Requests\FaqDeleteRequest;
use App\Http\Requests\PreviewDeleteRequest;
use App\Http\Requests\ShoppingOfferRequest;
use App\Http\Requests\WhoSuitedRequest;
use App\Http\Resources\Course\BidCourseResource;
use Illuminate\Http\Request;

//models
use App\Models\Course\Course;

//resources
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\CourseDocTakeResource;
use App\Http\Resources\CourseProcessResource;
use App\Models\Course\CourseDocImage;
use App\Models\Course\CourseLike;
use App\Models\Course\CourseProcess;
use App\Models\Course\ShoppingOffer as CourseShoppingOffer;
use App\Models\Course\WhoSuited as CourseWhoSuited;
use App\Services\CatalogService;
use App\Services\CourseService;
use App\Services\MainService;
use App\Services\UserServices;
use App\Traits\ApiResponser;
use Exception;
class CourseController extends Controller
{
    use ApiResponser;
    
    private $courseSrvice;
    private $userService;
    private $mainService;
    private $catalogService;

    public function __construct(UserServices $userService, CourseService $courseSrvice, MainService $mainService, CatalogService $catalogService)
    {
        $this->courseSrvice = $courseSrvice;
        $this->userService = $userService;
        $this->mainService = $mainService;
        $this->catalogService = $catalogService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::withCategoryCourse()
            ->withWhoSuited()
            ->withShoppingOffer()
            ->withFlows()
            ->withState()
            ->where(function($data){
                if(\request()->published === '1'){
                    return $data->active();
                }
            })
            ->get()
        ;
        return response()->json(CourseResource::collection($courses ?? []), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseStoreRequest $request)
    {
        $course = Course::find($request->course_id);
        $this->courseSrvice->addCategories($course, $request->category_ids);// filter
        $this->courseSrvice->addWhoRefinementTags($request);// filter
        $this->courseSrvice->addFlow($request, $course);// filter
        $this->courseSrvice->addDuration($request); // filter
        $this->courseSrvice->addDocs($request);
        $this->courseSrvice->addEduDocs($request);
        $this->courseSrvice->addShoppingOffers($request);
        $this->courseSrvice->addWhoSuited($request);
        $this->courseSrvice->addTagSearch($request);
        $this->courseSrvice->addFaq($request, $course);
        $this->courseSrvice->addSpecialities($request);
        $this->courseSrvice->addDirections($request);
        $this->courseSrvice->addUseTechnologies($request);
        $this->courseSrvice->addTeachers($request);
        $this->courseSrvice->addStudyDocs($request);


        $this->addDocTakeImages($request, $course);
        $this->addStudyPlanImages($request, $course);
        $this->addCalendarStudyScheduleImages($request, $course);
        $this->addSpecDocImages($request, $course);


        $data = $request->except([
            'course_id',
            'banner',
            'shopping_offers',
            'who_suited',
            'refinement_tag_ids',
            'category_ids',
            'search_tag_ids',
            'doc_edu_ids',
            'doc_ids',
            'doc_edu_ids',
            'doc_take_images',
            'is_restrict_block',
            'faqs',
            'specialities',
            'directions',
            'study_form_ids',
            'study_plan_images',
            'calendar_study_schedule_images',
            'use_technology_ids',
            'teacher_ids',
            'documents_images',
            'study_docs',
            'flows',
            'editor_image',
        ]);

        $this->courseSrvice->updateDatePublish($request, $course);
        
        if($prices = $this->courseSrvice->detectMinMaxPrices($course->flows()->with('packets')->get())){
            $data = array_merge($data, ['min_price' => $prices->min(), 'max_price' => $prices->max()]);
        }
        
        $course->update($data);

        return response()->json(CourseResource::make($course), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {   
        return response()->json(CourseResource::make($course), 200);
    }

    public function getBySlug(CourseBySlugRequest $request)
    {   
        $response = [
            'info' => $this->courseSrvice->info()
        ];
        if($request->slug){
            $response = array_merge($response, ['course' => CourseResource::make(Course::where('slug', $request->slug)->first())]);
        }
        return response()->json($response, 200);
    }


    /**
     * Удаление темы с вопросами для курса
     */
    public function destroyFaq(FaqDeleteRequest $request, Course $course)
    {
        if($this->courseSrvice->deleteFaq($request, $course)){
            return response()->json('faq deleted', 200);
        }
        return response()->json('faq not deleted', 200);
    }

    /**
     * Удаление ответа на вопрос для темы
     */
    public function destroyFaqAnswer(FaqDeleteRequest $request, Course $course)
    {
        if($this->courseSrvice->deleteFaqAnswer($request, $course)){
            return response()->json('faq answer deleted', 200);
        }
        return response()->json('faq answer not deleted', 200);
    }

    /**
     * Удаление вопроса для темы
     */
    public function destroyFaqQuestion(FaqDeleteRequest $request, Course $course)
    {
        if($this->courseSrvice->deleteFaqQuestion($request, $course)){
            return response()->json('faq question deleted', 200);
        }
        return response()->json('faq question not deleted', 200);
    }

    /**
     * Игформация для показа экрана "Создание курса" + информация о текущем курсе, если указан id курса
     */
    public function info(Request $request)
    {
        $response = [
            'info' => $this->courseSrvice->info()
        ];
        if(isset($request->ids)){
            $response = array_merge($response, ['courses' => $this->userService->getCourses($request)]);
        }
        return response()->json($response, 200);
    }

    /**
     * Добавление курса в избранное 
     */
    public function like(Course $course)
    {
        return response()->json($this->courseSrvice->like($course), 200);
    }

    /**
     * Добавить фото учебного плана
     */
    public function addStudyPlanImages(CourseStoreRequest $request, Course $course)
    {
        $result = $this->courseSrvice->addCourseDocImages($request->file('images'), $course->id, CourseDocImage::STUDY_PLAN_IMAGE_TYPE);
        if($result){
            return response()->json($result, 201);
        }
        return response()->json('error', 201);
    }

    /**
     * Добавить фото "Календарный учебный график"
     */
    public function addCalendarStudyScheduleImages(CourseStoreRequest $request, Course $course)
    {
        $result = $this->courseSrvice->addCourseDocImages($request->file('images'), $course->id, CourseDocImage::CALENDAR_STUDY_SHEDULE_IMAGE_TYPE);
        if($result){
            return response()->json($result, 201);
        }
        return response()->json('error', 201);
    }

    /**
     * Добавить фото документов спецраздела
     */
    public function addSpecDocImages(CourseStoreRequest $request, Course $course)
    {
        $result = $this->courseSrvice->addCourseDocImages($request->file('images'), $course->id, CourseDocImage::SPEC_DOC_IMAGE_TYPE);
        if($result){
            return response()->json($result, 201);
        }
        return response()->json('error', 201);
    }

    /**
     * Добавление фото документов, что выдаются по окончанию курса
     */
    public function addDocTakeImages(CourseStoreRequest $request, Course $course)
    {
        $result = $this->courseSrvice->addCourseDocImages($request->file('images'), $course->id, CourseDocImage::DOC_TAKE_IMAGE_TYPE);
        if($result){
            return response()->json($result, 201);
        }
        return response()->json('error', 201);
    }

    public function addPictureEditor(CourseStoreRequest $request)
    {
        if(isset($request->course_id)){
            $result = $this->mainService->addMedia(Course::PATH_EDITOR, $request->file('image'))->first();
            if($result && $request->course_id){
                Course::find($request->course_id)->update([
                    'editor_image' => $result['name']
                ]);
            }
            return response()->json($result, 201);
        }
        return response()->json('error', 201);
    }

    public function getPictureEditor()
    {
        $files = $this->mainService->getPictureEditor();
        return response()->json($files->toArray(), 200);
    }

    /**
     * Удаление фото документов, что выдаются по окончанию курса
     */
    public function deleteCourseDocImages(CRUDCourseDocImagesRequest $request, Course $course)
    {
        $result = $this->mainService->deleteImages($request->id, Course::PATH_IMG_SIMPLE);
        if($result){
            return response()->json('image deleted', 201);
        }
        return response()->json(['code' => 404, 'message' => 'Не удалось удалить картинку'], 404);
    }

    /**
     * 
     */
    public function deleteStudyDoc(CourseRequest $request, Course $course)
    {
        
        if($studyDocs = $course->studyDocs->find($request->study_doc_id)){
            $studyDocs->delete();
            return $this->success($course->studyDocs()->get());
        }
        return $this->error( 404, 'Документ не удалён либо его нет у данного курса', null, $course->studyDocs()->get());

    }

    /**
     * Удаление пункта блока "Кому подходит курс"
     */
    public function whsDelete(WhoSuitedRequest $request)
    {
        if($object = CourseWhoSuited::find($request->id)){
            $object->delete();
            return response()->json(['code' => 201, 'message' => 'Блок удалён'], 201);
        }
        return response()->json(['code' => 201, 'message' => 'Блок не удалён'], 201);
    }

    /**
     * Удаление пункта блока УТП
     */
    public function utpDelete(ShoppingOfferRequest $request)
    {
        if($object = CourseShoppingOffer::find($request->id)){
            $object->delete();
            return response()->json(['code' => 201, 'message' => 'Блок удалён'], 201);
        }
        return response()->json(['code' => 201, 'message' => 'Блок не удалён'], 201);
    }

    public function deleteRequiredDocument(DeleteRequiredDocumentRequest $request)
    {
        if($course = $this->courseSrvice->deleteRequiredDocument($request)){
            return response()->json(CourseResource::make($course), 200);
        }
        return response()->json('Документ не удалён', 200);
    }

    public function deleteRequiredEduDocument(DeleteRequiredEduDocumentRequest $request)
    {
        if($course = $this->courseSrvice->deleteRequiredEduDocument($request)){
            return response()->json(CourseResource::make($course), 200);
        }
        return response()->json('Документ не удалён', 200);
    }

    

    public function process(CourseProcessRequest $request)
    {
        $courseProcess = CourseProcess::updateOrCreate(
            $request->except(['type_id']),
            $request->all()
        );
        return response()->json(CourseProcessResource::make($courseProcess), 200);
    }

    public function addVideo(CourseVideoStoreRequest $request)
    {
        
        if($result = $this->courseSrvice->addVideo($request)){
            return response()->json($result, 201);
        }
        return response()->json('Видео не добавлено', 404);
    }

    public function deleteVideo(CourseVideoDeleteRequest $request)
    {
        if($this->courseSrvice->deleteVideo($request)){
            return response()->json(['code' => 201, 'message' => 'Видео удалёно'], 201);
        }
        return response()->json(['code' => 201, 'message' => 'Видео не удалёно'], 201);
    }

    public function addDocTake(CourseDocTakeStoreRequest $request)
    {
        if($this->courseSrvice->addDocTake($request)){
            $course = Course::findOrFail($request->course_id);
            return response()->json(CourseDocTakeResource::collection($course->docsTake), 201);
        }
        return response()->json(['code' => 201, 'message' => 'Видео не удалёно'], 201);
    }

    public function deleteDocTake(DocTakeDeleteRequest $request)
    {
        if($this->courseSrvice->deleteDocTake($request->id)){
            return response()->json(['code' => 201, 'message' => 'Документ удалён'], 201);
        }
        return response()->json(['code' => 500, 'message' => 'Документ не удалён'], 500);
    }

    public function deleteDocTakeImage(DocTakeImageDeleteRequest $request)
    {
        $docTakeImageIds = (new UserServices)->getDocTakeImageIds();
        if(!$docTakeImageIds->contains($request->id)){
            throw new Exception('Нет права на удаление этой картинки');
        }

        if($this->courseSrvice->deleteDocTakeImage($request->id)){
            return response()->json(['code' => 201, 'message' => 'Картинка удалена'], 201);
        }
        return response()->json(['code' => 500, 'message' => 'Картинка не удалена'], 500);
    }

    public function addPreview(AddPreviewRequest $request)
    {
        if($url = $this->courseSrvice->addPreview($request)){
            return response()->json(['code' => 201, $url], 201);
        }
        return response()->json(['code' => 500, 'message' => 'Preview не добавленно'], 500);

    }

    public function deletePreview(PreviewDeleteRequest $request)
    {
        if($this->courseSrvice->deletePreview($request)){
            return response()->json(['code' => 201, 'message' => 'Preview удалёно'], 201);
        }
        return response()->json(['code' => 201, 'message' => 'Preview не удалёно'], 201);
    }

    public function likes()
    {
        $courses = CourseLike::where('user_id', auth()->user()->id)->get(['course_id'])->pluck('course_id')->toArray();
        return response()->json($courses ?? [], 200);
    }
    
}