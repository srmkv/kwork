<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryCourse\CategoryCourseAddTagRequest;
use App\Http\Requests\CategoryCourse\CategoryCourseDeleteImageRequest;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryCourse\CategoryCourseDeleteRequest;
use App\Http\Requests\CategoryCourse\CategoryCourseImageRequest;
use App\Http\Requests\CategoryCourse\CategoryCourseStoreRequest;
use App\Http\Requests\CategoryCourse\CategoryCourseUpdateRequest;
use App\Http\Requests\CategoryCourseSpecialityRequest;
use App\Http\Requests\FaqSpecialityDeleteRequest;
//resources
use App\Http\Resources\Course\CategoryCourseResource;
use App\Http\Resources\Course\CategoryResource;
use App\Http\Resources\Course\CategoryCourseByIdResource;
use App\Http\Resources\Course\CategoryCourseShortResource;
//models
use App\Models\Course\CategoryCourse;
use App\Models\Course\CategoryCourseSpeciality;
use App\Models\Filter\FilterCategoryTag;
use App\Services\CategoryCourseService;

class CategoryCourseController extends Controller
{
    private $categoryCourseService;

    public function __construct(CategoryCourseService $categoryCourseService)
    {
        $this->categoryCourseService = $categoryCourseService;
    }

    public function index(Request $request)
    {
        $query = CategoryCourse::withParents();
        if(isset($request->published) && (int)$request->published === 1){
            $query = $query->active();
        }
        $result = $query->whereDoesntHave('parent');
        if(isset($request->id)){
            $result = $result->where('id', '!=', (int)$request->id);
        }
        return CategoryCourseResource::collection($result->get())->toArray($request);
    }

    public function allSpecialities(Request $request)
    {
        $result = $this->categoryCourseService->allSpecialities($request);
        return response()->json($result, 200);
    }

    public function list()
    {
        return CategoryCourseResource::collection(CategoryCourse::all());
    }

    public function all()
    {
        return CategoryCourseShortResource::collection(CategoryCourse::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryCourseStoreRequest $request)
    {   
        $categoryCourseIds = $this->categoryCourseService->createByParents($request);
        if($categoryCourseIds){
            return response()->json(CategoryCourseResource::collection(CategoryCourse::withParents()->withChildren()->whereIn('id', $categoryCourseIds)->get()), 200);
        }
        return response()->json('Не удалось создать категорию', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return response()->json(CategoryCourseByIdResource::make(
            CategoryCourse::findOrFail($request->id)
            )->toArray($request), 
        200);
    }

    /**
     * Дерево категорий с подкатегориями для страницы создания курса
     */
    public function getTree(Request $request)
    {
        $tree = [];
        $filter_tag = $request->filter_tag;

        if (isset($request->category_id)) {
            $category_id = $request->category_id;
            $tree = CategoryCourse::withChildren()->withFilterTag()->where('id', $category_id)->where('tag_id', $filter_tag)->get();
            return response()->json(CategoryResource::collection($tree),200);
        }elseif( !isset($request->category_id) && !isset($request->filter_tag)) {
            return response()->json(CategoryResource::collection(CategoryCourse::all()),200);
        }else {
            $tree = CategoryCourse::withChildren()->withFilterTag()->where('tag_id', $filter_tag)->get();
            return response()->json(CategoryResource::collection($tree), 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryCourseUpdateRequest $request)
    {
        $categoryCourse = CategoryCourse::findOrFail($request->id);
        $categoryCourse->update($request->all());
        return response()->json(CategoryCourseResource::make($categoryCourse), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoryCourseDeleteRequest $request)
    {
        CategoryCourse::findOrFail($request->id)->delete();
        return response()->json('Категория удалена', 201);
    }

    public function addTag(CategoryCourseAddTagRequest $request)
    {
        $filterTag = FilterCategoryTag::findOrFail($request->tag_id);
        $filterTag->categories()->detach();
        $filterTag->categories()->attach($this->categoryCourseService
            ->prepareFilterTagIds($request->category_ids, $request->tag_id));

        return response()->json('Тэг добавлен', 201);
    }

    /**
     * Удаление темы с вопросами для курса
     */
    public function destroyFaq(FaqSpecialityDeleteRequest $request)
    {
        if($this->categoryCourseService->deleteFaq($request)){
            return response()->json('faq deleted', 200);
        }
        return response()->json('faq not deleted', 200);
    }

    /**
     * Удаление ответа на вопрос для темы
     */
    public function destroyFaqAnswer(FaqSpecialityDeleteRequest $request)
    {
        if($this->categoryCourseService->deleteFaqAnswer($request)){
            return response()->json('faq answer deleted', 200);
        }
        return response()->json('faq answer not deleted', 200);
    }

    /**
     * Удаление вопроса для темы
     */
    public function destroyFaqQuestion(FaqSpecialityDeleteRequest $request)
    {
        if($this->categoryCourseService->deleteFaqQuestion($request)){
            return response()->json('faq question deleted', 200);
        }
        return response()->json('faq question not deleted', 200);
    }

    /**
     * Action для страницы категорий (специальностей) и т.д., переход по 
     * ссылкам главного меню
     * napravlenie=medicina&podnapravlenie=xirurgiia&tip-obuceniia=perepodgotovka&specialnost=neiroxirurgiia
     */
    public function categories(CategoryCourseSpecialityRequest $request)
    {
        $result = $this->categoryCourseService->pageSpeciality($request);
        return response()->json('ok', 200);
    }

    public function addImage(CategoryCourseImageRequest $request)
    {
        if($result = $this->categoryCourseService->addImage($request)){
            return response()->json($result, 200);
        }
        return response()->json('Картинка НЕ добавлена', 200);
    }

    public function deleteImage(CategoryCourseDeleteImageRequest $request)
    {
        if($this->categoryCourseService->deleteImage($request)){
            return response()->json('Картинка удалена', 200);
        }
        return response()->json('Картинка НЕ удалена', 200);
    }

}
