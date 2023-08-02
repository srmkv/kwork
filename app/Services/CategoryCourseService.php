<?php

namespace App\Services;

use App\Http\Requests\CategoryCourse\CategoryCourseDeleteImageRequest;
use App\Http\Requests\CategoryCourse\CategoryCourseImageRequest;
use App\Http\Requests\CategoryCourse\CategoryCourseStoreRequest;
use App\Http\Requests\CategoryCourseSpecialityRequest;
use App\Http\Requests\FaqSpecialityDeleteRequest;
use App\Http\Resources\Course\CategoryCourseResource;
use App\Http\Resources\FilterCategoryTagResource;
use App\Http\Resources\FilterCategoryTagSpecialityResource;
use App\Models\Course\CategoryCourse;
use App\Models\Course\CategoryCourseSpeciality;
use App\Models\Course\CategoryCourseSpecialityFaq;
use App\Models\Course\FilterTagCategoryItem;
use App\Models\Course\LevelEducation;
use App\Models\Filter\FilterCategoryTag;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CategoryCourseService
{
    public $mainService;

    public function __construct(MainService $mainService = null)
    {
        $this->mainService = $mainService;
    }

    public function getAllParentCategorySlug(array $ids)
    {
        $resultSlugs = collect();
        $baseSlug = '';
        $allCategories = CategoryCourse::whereIn('id', $ids)->withFilterTag()->get();
        
        foreach($ids as $id){
            $category = $allCategories->where('id', $id)->first();
            if($category){
                $category = $category->toArray();
                $filterTag = '';
                !isset($category['filter_tag']) || !count($category['filter_tag']) ?: $filterTag = $category['filter_tag']['slug'] . '_';
                $baseSlug === '' ? $baseSlug = $category['slug'] : $baseSlug .= '_' . $category['slug'];
                if($filterTag !== ''){
                    $resultSlugs->push($filterTag . $category['slug']);
                }
                $resultSlugs->push($baseSlug);
            }
        }
        return $resultSlugs->unique()->toArray();
    }

    public function getStaticPropertyRedisKeys(string $propName, array $ids)
    {
        $result = $propName::whereIn('id', $ids)->get()->map(fn($prop) => \Str::slug($prop::FILTER) . '_'. $prop->slug);
        return $result->unique()->values()->toArray();
    }

    public function prepareFilterTagIds(array $categoryIds, int $tag_id)
    {
        $result = collect();
        foreach($categoryIds as $categoryId){
            $result->push([
                'course_category_id' => $categoryId,
                'tag_id' => $tag_id,
            ]);
        }
        return $result->toArray();
    }

    /**
     * Не понятно как выдать на фронт созданную категорию с 1 parent_id, потому,
     * что по факту мы создадим к примеру 3 категории (по кол-ву parent_ids)
     *  прикреплённые каждая к своему родителю и которые имеют свой уникадьный id ???
     */
    public function createByParents(CategoryCourseStoreRequest $request)
    {
        $tree = collect($request->parent_ids);
        $dataForUpdate = $request->except(['id','parent_ids', 'speciality']);

        $newCategoryIds = collect();
        if($tree !== null && $tree->count() > 0){
            $mainTree = $tree->shift();
            $mainCategory = CategoryCourse::find($request->id);
            $mainCategory->update(['parent_id' => collect($mainTree)->last(), 'tree' => json_encode($mainTree)]);
            
            $newCategoryIds->push($request->id);
            foreach($tree as $parentIds){
                $checkData = ['slug' => $mainCategory->slug, 'parent_id' => collect($parentIds)->last(), 'tree' => json_encode($parentIds)];
                $dataForUpdate = array_merge(collect($mainCategory->toArray())->except(['id','courses','count_courses'])->toArray(), ['parent_id' => collect($parentIds)->last(), 'tree' => json_encode($parentIds)]);
                if ($categoryCourse = CategoryCourse::where($checkData)->first()) {
                    $categoryCourse->update($dataForUpdate);
                } else {
                    $categoryCourse = CategoryCourse::create($dataForUpdate);
                }
                
                if($request->speciality){
                    $this->addSpeciality($request->speciality, $categoryCourse->id);
                }
                $newCategoryIds->push($categoryCourse->id);
            }
            $newCategoryIds = $newCategoryIds->unique()->toArray();
            CategoryCourse::whereIn('id', $newCategoryIds)->update(['main_parent_ids' => json_encode($newCategoryIds)]);
            return $newCategoryIds;
        }elseif($request->parent_ids !== null && count($tree) == 0){
            $dataForUpdate = array_merge($request->except(['parent_ids','speciality']), ['parent_id' => null, 'tree' => null]);
        }
        $categoryCourse = CategoryCourse::updateOrCreate(
            ['id' => $request->id ?? 0],
            $dataForUpdate
        );
        CategoryCourse::likeMainParentIds($request->id)->update($request->except(['id', 'parent_ids', 'speciality']));

        if($request->speciality){
            $this->addSpeciality($request->speciality, $request->id);
        }
        return [$categoryCourse->id];
    }

    private function addSpeciality(array $speciality, int $categoryCourseId)
    {
        $speciality = collect($speciality);
        $categoryCourseSpecialityId = CategoryCourseSpeciality::updateOrCreate(
            ['category_course_id' => $categoryCourseId ?? 0],
            array_merge($speciality->except(['faqs'])->toArray(),['category_course_id' => $categoryCourseId])
        )->id;

        if(isset($speciality['faqs'])){
            $this->addFaqs($speciality['faqs'], $categoryCourseSpecialityId);
        }
    }

    public function addFaqs($faqs, int $categoryCourseSpecialityId)
    {
        foreach($faqs as $itemFaq){
            $faq = CategoryCourseSpecialityFaq::updateOrCreate(
                ['id' =>  $itemFaq['id'] ?? 0],
                [
                    'title' => $itemFaq['title'],
                    'answer' => $itemFaq['answer'],
                    'position' => $itemFaq['position'],
                    'contains_subsections' => $itemFaq['contains_subsections'],
                    'course_speciality_id' => $categoryCourseSpecialityId,
                ]
            );
            if($itemFaq['questions']){
                $this->addQuestions($itemFaq['questions'], $faq);
            }
        }
        return true;
    }

    private function addQuestions(Array $questions, CategoryCourseSpecialityFaq $faq)
    {
        foreach($questions as $questionItem){
            $question = $faq->questions()->updateOrCreate(
                ['id' =>  $questionItem['id'] ?? 0],
                ['title' => $questionItem['title'], 'position' => $questionItem['position']]);
            $question->answer()->updateOrCreate(
                ['id' =>  $questionItem['id'] ?? 0], 
                ['text' => $questionItem['answer'], 'faq_question_id' => $question->id]
            );
        }
    }

    /**
     * Удаление раздела FAQ + рекурсивное удаление всех связанных сущностей (вопрос/ответ)
     */
    public function deleteFaq(FaqSpecialityDeleteRequest $request)
    {
        if($faq = CategoryCourseSpecialityFaq::find($request->id)){
            $faq->questions->each(fn($question) => $question->answer()->delete());
            $faq->questions()->delete();
            return $faq->delete();
        }
        return false;
    }

    /**
     * Удаление ответа к вопросу у определённого раздела FAQ
     */
    public function deleteFaqAnswer(FaqSpecialityDeleteRequest $request)
    {
        if($faq = CategoryCourseSpecialityFaq::find($request->id)){
            return $faq->questions()->find($request->question_id)->answer()->delete();
        }
        return false;
    }

    /**
     * Удаление вопроса у определённого раздела FAQ
     */
    public function deleteFaqQuestion(FaqSpecialityDeleteRequest $request)
    {
        if($faq = CategoryCourseSpecialityFaq::find($request->id)){
            if($this->deleteFaqAnswer($request)){
                return $faq->questions()->find($request->question_id)->delete();
            }
        }
        return false;
    }

    public function pageSpeciality(CategoryCourseSpecialityRequest $request)
    {
        $parentTree = $this->detectAllParentTree(collect($request->all()));
        $successCategory = $this->detectSuccessTree($parentTree);
        return $parentTree;
    }

    public function detectAllParentTree(Collection $params)
    {
        $params->pop();

        $tree = collect();
        $buf = collect();

        $params->each(function($categoryName, $tagName)use($tree, $buf){
            $tagId = FilterCategoryTag::where('slug', $tagName)->first()->id;
            $categoriesSlugs = explode(',', $categoryName);
            foreach($categoriesSlugs as $slug){
                $categories = CategoryCourse::where('tag_id', $tagId)->where('slug', $slug)->get(['id'])->pluck('id');
                if($categories->count() === 1){
                    $buf->push($categories[0]);

                }elseif($categories->count() > 1){
                    // если попался массив с 1+ категориями,
                    // перебираем их и сливаем каждый с тем что уже есть buf
                    foreach($categories as $catId){
                        $tree->push(array_merge($buf->toArray(), [$catId]));
                    }
                }
            }
        });
        return $tree;
    }

    public function detectSuccessTree(Collection $trees)
    {
        foreach($trees as $tree){
            if($category = CategoryCourse::where('tree', json_encode($tree))->first()){
                return $category;
            }
        }
        return false;
    }

    /**
     * Вывод специальностей с учётом фильтров и сортировки 
     * + пагинация 
     * + фильтры для страницы специальностей
     */
    public function allSpecialities(Request $request)
    {
        $dataSpecialities = $this->getSpecialitiesByParams($request);

        $filters = $this->filtersForSpecialities($dataSpecialities['data']);

        return [
            'specialities' => CategoryCourseResource::collection($dataSpecialities['data']),
            'filters' => $filters,
            'totalCount' => $dataSpecialities['totalCount'],
            'limit' => $dataSpecialities['limit'],
            'per_page' => $dataSpecialities['per_page'],
            'page' => $dataSpecialities['page'],
            'sort' => join('.',$dataSpecialities['sort']),
        ];
    }

    /**
     * Собираем список актуальных фильтров на основе полученных специальнгостей
     */
    protected function filtersForSpecialities($specialities)
    {
        $specialityFilters = $this->getFiltersBySpecialities($specialities);
        $result = $this->getAllFiltersForSpeciality($specialityFilters);
        return $result;
    }

    /**
     * Получение специальностей с данными для пагинации
     */
    protected function getSpecialitiesByParams(Request $request)
    {
        $specialities = $this->createQuery($request->all());
        $totalCount = $specialities->count();
        $limit = (int)$request->limit ?: config('catalog.limit');
        $page = (int)$request->page;
        $page <= 1 ? $offset = 0 : $offset = (($page - 1) * $limit);
        $per_page = intval(ceil($totalCount / $limit));
        $data = $specialities->get()->unique('title');

        return [
            'sort' => (new CatalogService)->parseSortParams($request->sort),
            'page' => $page,
            'offset' => $offset,
            'totalCount' => $totalCount,
            'limit' => $limit,
            'per_page' => $per_page,
            'data' => $data,
        ];
        
    }

    public function getAllFiltersForSpeciality($specialityFilters)
    {
        $result = collect();
        $data = CategoryCourse::whereIn('tag_id', CategoryCourse::SPECIALITY_FILTER_IDS)->get(['id', 'title', 'slug', 'tag_id'])->groupBy('tag_id');
        $filters = FilterCategoryTag::whereIn('id', CategoryCourse::SPECIALITY_FILTER_IDS)->get(['id', 'title', 'slug'])->groupBy('id');
        foreach($data as $filterId => $categories){
            if(0){ // Пока закоментим, не понятно надо  ли как отфильтровывать фильтры
                $categories = $categories->filter(function($item)use($specialityFilters){
                    return $specialityFilters['napravlenie']->contains($item->id)
                        || $specialityFilters['specialities']->contains($item->id)
                    ;
                });
            }
            $result->push(
                array_merge($filters->get($filterId)->first()->toArray(),[
                    'children' => $categories
                        ->unique('slug')
                        ->toArray()
                ])
            );
        }

        $levelEducations = LevelEducation::whereIn('id', $specialityFilters['levelEducation']->toArray())
            ->get(['id', 'title', 'slug'])
            ->each(function(&$item){
                $item['tag_id'] = 0;
                $item['count_courses'] = 0;
            })
        ;

        $result->push([
            'id' => 3,
            'title' => LevelEducation::FILTER,
            'slug' => LevelEducation::slug(),
            'children' => $levelEducations->toArray()
        ]);
        return $result->sortBy('id')->values();
    }

    public function getFiltersBySpecialities($specialities)
    {   
        $result = collect([
            'napravlenie' => collect(),
            'levelEducation' => collect(LevelEducation::all(['id'])->pluck('id')->toArray()),
            'specialities' => collect(),
        ]);

        $specialities->each(function($speciality)use(&$result){
            $result['napravlenie']->push(json_decode($speciality->tree)[0]);
            $result['specialities']->push($speciality->id);
        });

        return $result->map(fn($item)=> $item = $item);
    }

    /**
     * Формируем запрос в БД с учётом фильтров
     */
    public function createQuery(array $params)
    {
        $query = CategoryCourse::specialities();
        foreach($params as $tagSlug => $categorySlugs){
            $explodeData = explode(',', $categorySlugs);
            if($tagSlug === 'napravlenie'){
                $napravlenies = CategoryCourse::whereIn('slug', $explodeData)->get(['id'])->pluck('id')->toArray();
                $query = $query->where(function ($q) use($napravlenies) {
                    foreach($napravlenies as $napravlenie){
                       $q->orwhere('tree', 'like',  '[' . $napravlenie .'%');
                    }      
                });
                continue;
            }

            if($tagSlug === 'tip-obuceniia'){
                $tip_obuceniias = CategoryCourse::whereIn('slug', $explodeData)->get(['id'])->pluck('id')->toArray();
                $query = $query->where(function ($q) use($tip_obuceniias) {
                    foreach($tip_obuceniias as $tip_obuceniia){
                       $q->orwhere('tree', 'like',  '%,' . $tip_obuceniia .'%');
                    }      
                });
                continue;
            }
            
            if($tagSlug === 'specialnost'){
                $query = $query->whereIn('slug', $explodeData);
                continue;
            }
            
            if($tagSlug === 'uroven-obrazovaniia'){
                $levelEduIds = LevelEducation::whereIn('slug', $explodeData)->get(['id'])->pluck('id')->toArray();
                $query = $query->whereHas('speciality', function($q)use($levelEduIds){
                    $q->whereIn('level_education_id', $levelEduIds);
                });
                continue;
            }
            
            if($tagSlug === 'sort'){
                $sort = (new CatalogService)->parseSortParams($categorySlugs);
                $query = $query->orderBy($sort['field'], $sort['direction']);
                continue;
            }

            if($tagSlug === 'query'){
                $query = $query->where('title', 'LIKE', '%' . $categorySlugs . '%');
                continue;
            }

        }

        return $query;
    }

    public function parseUrlParams(array $params)
    {
        $redisKeys = collect();
        $cacheParams = collect();
        foreach($params as $key => $param){
            $paramPrts = \Str::of($param)->explode(',');
            $cacheParams->push([$key => $paramPrts]);
            $paramPrts = $paramPrts->map(fn($item) => $key . '_' . $item);
            $redisKeys->push($paramPrts);
        }

        return $redisKeys;
    }

    public function addImage(CategoryCourseImageRequest $request)
    {
        $image = $request->image;
        $result = $this->mainService->addMedia(CategoryCourse::PATH_IMG, $image);

        if($result){
            if($imageData = $result->first()){
                CategoryCourse::find($request->id)->update([
                    'image' => $imageData->get('name')
                ]);
                return $imageData->get('name');
            }
            return false;
        }
        return false;
    }

    public function deleteImage(CategoryCourseDeleteImageRequest $request)
    {
        if($this->mainService->deleteMedia(CategoryCourse::PATH_IMG, CategoryCourse::find($request->id)->image)){
            return true;
        }
        return false;
    }

    
}