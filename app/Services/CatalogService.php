<?php

namespace App\Services;

use App\Http\Requests\CatalogCourseRequest;
use App\Models\Course\Address;
use App\Models\Course\CategoryCourse;
use App\Models\Course\Course;
use App\Models\Course\StudyDuration;
use App\Models\Course\StudyForm;
use App\Models\Course\TagRefinement;
use App\Models\Course\TagSearchCourse;
use App\Models\Filter\FilterCategoryTag;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
class CatalogService
{
    const REDIS_OPERATION_OR = 'or';
    const REDIS_OPERATION_AND = 'and';
    const REDIS_RESULT_CATALOG_NAME = 'resultCatalogName';
    const REDIS_FINAL_RESULT_CATALOG_NAME = 'finalResultCatalogName';
    
    const REDIS_FILTER_PRICE_KEY = 'price';
    const REDIS_FILTER_DATE_KEY = 'date';

    const CACHE_COURSE_CATEGORIES_SLUGS_REMEMBER_PREFIX = 'courseCategoriesSlugs=';
    const CACHE_COURSE_CATEGORIES_SLUGS_HASH_NAME = 'courseCategoriesSlugsHashName';
    const CACHE_COURSES_REMEMBER_PREFIX = 'courses=';
    const CACHE_COURSE_IDS_REMEMBER_PREFIX = 'courseIds=';
    const CACHE_REQUEST_PARAMS_PREFIX = 'cacheRequestParams=';

    const CACHE_ALL_CATEGORIES_SLUGS = 'cacheAllCategoriesSlugs';
    const CACHE_CHECKED_CATEGORIES_SLUGS = 'cacheCheckedCategoriesSlugs';

    
    public function catalog(CatalogCourseRequest $request, $redisKeys)
    {
        // $redisKeys = $this->parseUrlParams($request->except($this->getExceptUrlParams()));
        // a75a16b0a69e10a59aca046bce7634d9
        $courseCategoriesSlugsHashName = md5($redisKeys->unique()->collapse()->join('+') /*. $request->date_min . $request->date_max*/);

        /**
            napravlenie:
                "it"
                "po"
            podnapravlenie:
                "draivera"
         */
        $checkedFilters = Cache::get(CatalogService::CACHE_REQUEST_PARAMS_PREFIX . $courseCategoriesSlugsHashName) ?? [];

        // ["it","po"]
        $checkedMainCategorySlugs = count(array_values($checkedFilters)) > 0 ? array_values($checkedFilters)[0] : [];

        /**
         * ["napravlenie_po"]
         * ["podnapravlenie_draivera"]
         */
        if($redisKeys->count() > 0 &&  $redisKeys->first()->count() > 1 && CategoryCourse::whereIn('slug', $checkedMainCategorySlugs)->count()){
            $redisKeys->first()->shift();
        }
        
        $courseIds = Cache::remember(self::CACHE_COURSE_IDS_REMEMBER_PREFIX . $courseCategoriesSlugsHashName, config('cache.ttl-catalog-main'), function () use($redisKeys) {
            $courseIds = $this->getCourseIds($redisKeys->unique()->toArray());
            return $courseIds;
        });

        $totalCount = count($courseIds);
        $limit = (int)$request->limit ?: config('catalog.limit');
        $page = (int)$request->page;
        $page <= 1 ? $offset = 0 : $offset = (($page - 1) * $limit);
        $per_page = intval(ceil($totalCount / $limit));

        return [
            'sort' => $this->parseSortParams($request->sort),
            'page' => $page,
            'offset' => $offset,
            'totalCount' => $totalCount,
            'limit' => $limit,
            'per_page' => $per_page,
            'data' => $courseIds,
            'prices' => $this->parsePriceParams($courseIds),
            self::CACHE_COURSE_CATEGORIES_SLUGS_HASH_NAME => $courseCategoriesSlugsHashName
        ];
    }

    public function filters(CatalogCourseRequest $request)
    {
        // $redisKeys = $this->parseUrlParams($request->except($this->getExceptUrlParams()));
        /**
         * ["napravlenie_po"]
         * ["podnapravlenie_draivera"]
         */
        $redisKeys = $this->parseUrlParams($request->except($this->getExceptUrlParams()));
        //a75a16b0a69e10a59aca046bce7634d9
        $courseCategoriesSlugsHashName = md5($redisKeys->unique()->collapse()->join('+') /*. $request->date_min . $request->date_max*/);
        
        // берём то что выбранно в фильтре в виде многомерного массива слагов
        /**
            napravlenie:
                "it"
                "po"
            podnapravlenie:
                "draivera"
         */
        $checkedFilters = Cache::get(CatalogService::CACHE_REQUEST_PARAMS_PREFIX . $courseCategoriesSlugsHashName) ?? [];

        // ["napravlenie","podnapravlenie"]
        $checkedFilterTagSlugs = array_keys($checkedFilters);

        // ["it","po"] Проблема возможно из за того, что присутствует самое верхнее направление "ИТ"
        $checkedMainCategorySlugs = count(array_values($checkedFilters)) > 0 ? array_values($checkedFilters)[0] : [];

        if($redisKeys->count() > 0 &&  $redisKeys->first()->count() > 1 && CategoryCourse::whereIn('slug', $checkedMainCategorySlugs)->count()){
            $redisKeys->first()->shift();
        }
        $courseIds = Cache::get(CatalogService::CACHE_COURSE_IDS_REMEMBER_PREFIX . $courseCategoriesSlugsHashName) ?? [];

        $extraFiltered = $this->getExtraFiltered($request, $checkedFilterTagSlugs);
        
        // все тэги (Фильтры)
        /**
         ["napravlenie","podnapravlenie","tip-obuceniia","specialnost","razriad"]
         */
        $allFilterTags = FilterCategoryTag::all(['id','title','slug']);

        // изначальный вид фильтров, только с корневыми категориями
        /**
         {
            id: 1
            title: "Направление"
            slug: "napravlenie"
         }
         */
        $filters = collect(FilterCategoryTag::whereHas('categories', function($query){
            $query->whereDoesntHave('parent')->active();
        })->get(['id','title','slug']));

        // Первый фильтр Направление с категориями Направление
        foreach($filters as &$filter){
            $filter['categories'] = $filter->categories()->active()->whereDoesntHave('parent')->get(['id','title','slug','tag_id','parent_id','tree'])->map(function($mainCategory){
                $mainCategory->categories = $mainCategory->childrenWithSelfTag()->active()->get(['id','title','slug','tag_id','parent_id','tree'])->toArray();
                return $mainCategory;
            })->toArray();
        }

        $mainPoint = '';
        if(count($checkedMainCategorySlugs) > 0){
            $mainPoint = $checkedMainCategorySlugs[0];
            if(CategoryCourse::where('slug', $mainPoint)->whereNull('parent_id')->count() == 0){
                $mainPoint = '';
            }
        }

        $result = $filters->toArray();

        // Придумать как не затерать в result первоначальный Фильтр с направлениями + пушить выбранному, дочек
        foreach($filters as &$filter){
            $filter['categories'] = $filter->categories()->whereDoesntHave('parent')->active()
                ->get(['id','title','slug','tag_id','parent_id','tree'])->map(function($mainCategory)use($mainPoint, &$result, $allFilterTags, $filter){
                    $childrenWithSelfTagIds = [];
                    if($mainCategory['slug'] === $mainPoint){

                        foreach($result as &$filter){
                            foreach($filter['categories'] as &$category){
                                if($category['id'] === $mainCategory['id']){
                                    $childrenWithSelfTag = $mainCategory->childrenWithSelfTag()->active()->get(['id','title','slug','tag_id','parent_id','tree']);
                                    $childrenWithSelfTagIds = $childrenWithSelfTag->pluck('id')->values()->toArray();
                                    $category['categories'] = $childrenWithSelfTag->toArray();
                                }  
                            }
                        }

                        $allOtherChildren = $mainCategory->allChildrens()
                            ->active()
                            ->whereNotIn('id', $childrenWithSelfTagIds)
                            ->get(['id','title','slug','tag_id', 'main_parent_ids','parent_id','tree'])
                            ->unique('main_parent_ids')
                            ->groupBy('tag_id')
                            ->toArray();

                        foreach($allOtherChildren as $tagId => $filterCategories){
                            if(collect($result)->where('id', $tagId)->count() == 0){
                                $newFilter = $allFilterTags->where('id', $tagId)->first()->toArray();
                                $newFilter['categories'] = $filterCategories;
                                $result = array_merge($result, [$newFilter]);
                            }
                        }
                    }
                    return $mainCategory;
            })->toArray();
        }

        $studyForms = [[
            'title' => StudyForm::FILTER,
            'slug' => \Str::slug(StudyForm::FILTER),
            'categories' => StudyForm::all(['id','title','slug'])->toArray(),
            'static' => true
        ]];

        // Особенность обучения
        $tagRefinements = [[
            'title' => TagRefinement::FILTER_TITLE,
            'slug' => \Str::slug(TagRefinement::FILTER),
            'categories' => TagRefinement::all(['id','title','slug'])->toArray(),
            'static' => true
        ]];

        $addresses = [[
            'title' => Address::FILTER,
            'slug' => \Str::slug(Address::FILTER),
            'categories' => Address::all(['id','title','slug'])->toArray(),
            'static' => true
        ]];


        $durations = [[
            'title' => StudyDuration::FILTER,
            'slug' => \Str::slug(StudyDuration::FILTER),
            'categories' => StudyDuration::all(['id','title','slug'])->toArray(),
            'static' => true
        ]];


        $filters = array_merge(collect($result)->sortBy('id')->toArray(),  $tagRefinements, $durations, $studyForms, $addresses);

        $filters = $this->filteredFilters($filters, $courseIds, $extraFiltered, $checkedFilters);
        
        return $filters;
        
    }

    public function getCourses($catalog)
    {
        $courses = Cache::remember(CatalogService::CACHE_COURSES_REMEMBER_PREFIX . 
            $catalog[CatalogService::CACHE_COURSE_CATEGORIES_SLUGS_HASH_NAME], config('cache.ttl-catalog-main'), function () use($catalog) {
                $query = Course::withCategoryCourse()
                    ->active()
                    ->actual()
                    ->withWhoSuited()
                    ->withShoppingOffer()
                    ->withFlows()
                    ->whereIn('id', $catalog['data'])
                ;

                $query->orderBy($catalog['sort']['field'], $catalog['sort']['direction']);

                $courses = $query->get();

                if($courses->count() === 0){
                    $courses = null;
                }
                
                return $courses;
        });
        return $courses;
    }

    /**
     * Фильтрация пунктов фильтра, в зависимости от контекста
     * уже отобранных курсов в каталоге
     */
    public function filteredFilters(array $filters, $courseIds, $extraFiltered, $checkedFilters)
    {
        $actualProperties = $this->getActualProperties($courseIds);
        // $actualProperties->shift();

        foreach($filters as &$filter){
            $this->recurciveFiltered($filter, $actualProperties, $extraFiltered, $checkedFilters);
        }

        return array_values($filters);
    }

    private function getActualProperties($courseIds)
    {
        $actualProperties = Course::with('categoryCourse.children')
        ->withAddresses()
        ->withDuration()
        ->withStudyForms()
        ->withTagRefinements()
        ->whereIn('id', $courseIds)->get()->map(function($course){
            $properties = collect();
            return $properties->merge($course->categoryCourse->pluck('slug')->toArray())
                ->merge($course->addresses->pluck('slug')->toArray())
                ->merge($course->duration->pluck('slug')->toArray())
                ->merge($course->studyForms->pluck('slug')->toArray())
                ->merge($course->tagRefinements->pluck('slug')->toArray())
            ;
        })->collapse()->unique();

        return $actualProperties;
    }

    private function recurciveFiltered(array &$filter, Collection $actualProperties, $extraFiltered, $checkedFilters)
    {
        if(isset($filter['categories']) && $filter['categories'] !== null && count($filter['categories'])){
            
            foreach($filter['categories'] as $key => &$category){

                if($extraFiltered->get($filter['slug'])){
                    $actualProperties = $this->getActualProperties($extraFiltered->get($filter['slug']));
                }

                if(!$actualProperties->contains($category['slug'])){
                    if(!isset($category['static'])  && array_key_exists('parent_id', $category) && $category['parent_id'] == null){
                        continue;
                    }

                    if(!collect($checkedFilters)->collapse()->contains($category['slug'])){
                        unset($filter['categories'][$key]);
                    }

                    continue;
                }
            }
            $filter['categories'] = array_values($filter['categories']);
        }
        return $filter;
    }

    public function preparePricesForCatalog($min_price, $max_price)
    {
        if((int)$max_price === (int)$min_price) return $min_price;

        $min_whole_part = (int)$min_price - ((int)$min_price % Course::PRICE_DELIMETER);
        $max_whole_part = (int)$max_price - ((int)$max_price % Course::PRICE_DELIMETER) + 
            ((int)$max_price % Course::PRICE_DELIMETER > 0 ? Course::PRICE_DELIMETER : 0);

        $arrPartKeys = collect();
        $maxCounter = $max_whole_part % Course::PRICE_DELIMETER > 0 ? $max_whole_part + Course::PRICE_DELIMETER : $max_whole_part;

        for($i = $min_whole_part; 
            $i <= $maxCounter; 
            $i += Course::PRICE_DELIMETER
        ){
            $dataForAdd = $i;
            $addPerion = false;

            if($i % 100 !== 0){
                $addPerion = true;
            }

            if($min_price == ($i + (int)$min_price % Course::PRICE_DELIMETER) && $min_price != $i){
                $addPerion = true;
            }

            if($addPerion){
                $dataForAdd = $dataForAdd . '-' . ($i + Course::PRICE_DELIMETER);
            }

            if(!$addPerion && $dataForAdd - $max_price < 100 && $dataForAdd - $max_price > 0){
                break;
            }
            $arrPartKeys->push($dataForAdd);

            if(!$addPerion && $i + Course::PRICE_DELIMETER <= $maxCounter){
                $arrPartKeys->push($dataForAdd . '-' . ($i + Course::PRICE_DELIMETER));
            }
        }
        return $arrPartKeys->join(',');
        
    }

    public function prepareDatesForCatalog($date_min, $date_max)
    {
        if(!$date_min || !$date_max){
            return null;
        }
        $period = CarbonPeriod::create($date_min, $date_max);
        $arrDates = collect();
        foreach($period as  $date){
            $arrDates->push($date->format('Y-m-d'));
        }
        return $arrDates->values()->join(',');
    }

    //                                                          ["napravlenie","podnapravlenie"]
    public function getExtraFiltered(CatalogCourseRequest $request, $checkedFilterTagSlugs)
    {
        // Здесь проблемма!!!!!
        $extraFiltered = collect();
        foreach($checkedFilterTagSlugs as $checkedCategories){
            // 1. ["podnapravlenie_draivera"]
            // 2. ["napravlenie_it", "napravlenie_po"]
            $keys = $this->parseUrlParams($request->except([$checkedCategories, ...$this->getExceptUrlParams()]), true);
            
            $courseIds = $this->getCourseIds($keys->unique()->toArray());
            $extraFiltered->push([
                $checkedCategories => $courseIds
            ]);
        }
        return $extraFiltered->collapse();
        
    }

    private function parsePriceParams($courseIds)
    {
        $courses = Course::whereIn('id', $courseIds)->get();
        $result = [
            'min_price' => $courses->min('min_price'),
            'max_price' => $courses->max('max_price'),
        ];
        // if($prices){
        //     $pricesPart = explode(',', $prices);
        //     $result = [
        //         'min_price' => (int)$pricesPart[0],
        //         'max_price' => isset($pricesPart[1]) ? (int)$pricesPart[1] : (int)$pricesPart[0]
        //     ];
        // }
        return $result;
    }

    public function parseSortParams($sort){
        $result['field'] = 'date_min';
        $result['direction'] = 'asc';

        if($sort){
            $sortParts = explode('.',$sort);
            $result['field'] = $sortParts[0];
            $result['direction'] = $sortParts[1];
            if($result['field'] == 'price'){
                $result['field'] = $result['direction'] == 'desc' ? 'max_price' : 'min_price';
            }
            if($result['field'] == 'title'){
                $result['field'] = 'slug';
            }
            if($result['field'] == 'date_published'){
                $result['field'] = 'date_min';
            }
        }

        return $result;
    }

    /**
     * Разбираем параметры запроса и генирируем ключи для рэдис
     */
    public function parseUrlParams(array $params, $filter = false)
    {
        $redisKeys = collect();
        $cacheParams = collect();
        $mainCategoriesSlugs = CategoryCourse::mainCategoriesSlugs();
        
        foreach($params as $key => $param){
            $paramPrts = \Str::of($param)->explode(',');
            if($filter && count($paramPrts) > 1){
                $paramPrts = $paramPrts->filter(fn($part) => !$mainCategoriesSlugs->contains($part));
            }
            $cacheParams->push([$key => $paramPrts]);
            $paramPrts = $paramPrts->map(fn($item) => $key . '_' . $item);
            $redisKeys->push($paramPrts);
        }

        Cache::set(self::CACHE_REQUEST_PARAMS_PREFIX . md5($redisKeys->unique()->collapse()->join('+')), 
            $cacheParams->collapse()->unique()->toArray());
        return $redisKeys;
    }

    protected function getCourseIds(array $keys)
    {
        if(count($keys) === 0){
            return Course::all(['id'])->pluck(['id'])->unique()->toArray();
        }
        $allResultNames = collect();
        foreach($keys as $index => $categorySlugs){
            $nameResult = self::REDIS_RESULT_CATALOG_NAME . $index;
            Redis::bitOp(self::REDIS_OPERATION_OR, $nameResult, ...$categorySlugs);
            $allResultNames->push($nameResult);
        }
        
        Redis::bitOp(self::REDIS_OPERATION_AND, self::REDIS_FINAL_RESULT_CATALOG_NAME, ...$allResultNames->toArray());
        $hashBitMap = Redis::get(self::REDIS_FINAL_RESULT_CATALOG_NAME);
        $ids = $this->convertBitmapToIds($hashBitMap);
        return $ids;
    }

    public function getAllRedisKeys()
    {
        $keys = collect(Redis::keys('*'));
        return $keys->map(fn($key) => $key = \Str::replace('backqual_database_', '', \Str::replace('laravel_database_', '', $key)));
    }

    public function clearRedis()
    {
        return Redis::flushall();
    }

    public function clearCache()
    {
        return Cache::flush();
    }

    /**
     * Создаёт побитовую матрицу в Redis используя сочетания слагов категорий,
     * подкатегорий и других свойств курса
     */
    public function createBitMap(array $propertyValueSlugs, int $course_id, string $propertySlug = '', $value = 1)
    {
        $keys = collect();
        if($propertyValueSlugs && $course_id){
            foreach($propertyValueSlugs as $propValSlug){
                $key = $propertySlug !== '' ? \Str::slug($propertySlug) . '_' . (string)$propValSlug : (string)$propValSlug;
                Redis::setBit($key, $course_id, $value);
                $keys->push($key);
            }   
        }
    }

    private function convertBitmapToIds($hashBitMap, $lastPos = 0)
    {
        $result_key = $this->getBitmap($hashBitMap);
        $needle = "1";
        $positions = array();
        while (($lastPos = strpos($result_key, $needle, $lastPos))!== false){
            $positions[] = $lastPos;
            $lastPos = $lastPos + strlen($needle);
        }
        return $positions;
    }

    private function getBitmap($bitmap){
        $bytes = unpack('C*', $bitmap);
        $bin = join(array_map(function($byte){
            return sprintf("%08b", $byte);
        }, $bytes));
        return $bin;
    }

    public function searchByTitle(Collection $courses, Collection $search)
    {
        $countEquals = 0;
        return $courses->filter(function($item) use ($search, $countEquals) {
            $result = false;
            if($search->count()){
                foreach($search as $sItem){
                    if(stripos(\Str::lower($item['name']), \Str::lower($sItem)) !== false){
                        $result = true;
                        $countEquals++;
                    }
                }
                $word_count = count(preg_split('/\s+/', $item['name']));
                $item['pesentEquals'] = round($countEquals / ($word_count === 0 ? 1 : $word_count) * 100, 1);
            }
            return $result;
        });
    }

    public function searchByDescription(Collection $courses, Collection $search)
    {
        $countEquals = 0;
        return $courses->filter(function($item) use ($search, $countEquals) {
            $result = false;
            if($search->count()){
                foreach($search as $sItem){
                    if(stripos(\Str::lower($item['short_description']), \Str::lower($sItem)) !== false){
                        $result = true;
                        $countEquals++;
                    }
                }
                $word_count = count(preg_split('/\s+/', $item['short_description']));
                $item['pesentEquals'] = round($countEquals / ($word_count === 0 ? 1 : $word_count) * 100, 1);
            }
            return $result;
        });
    }

    public function searchByStringValues(Collection $courses, Collection $search, string $fieldName)
    {
        $countEquals = 0;
        return $courses->filter(function($item) use ($search, $countEquals, $fieldName) {
            $result = false;
            if($search->count()){
                foreach($search as $sItem){
                    if(stripos(\Str::lower($item[$fieldName]), \Str::lower($sItem)) !== false) {
                        $result = true;
                        $countEquals++;
                    }
                }
                $word_count = count(preg_split('/\s+/', $item[$fieldName]));
                $item['pesent_'. $fieldName .'_equals'] = round($countEquals / ($word_count === 0 ? 1 : $word_count) * 100, 1) * $countEquals;
            }
            return $result;
        });
    }

    public function searchByTag(Collection $courses, Collection $search)
    {
        return $courses->filter(function($item) use ($search) {
            
            if($search->count()){
                $item['pesent_tag_equals'] = $item->searchTags()->where(function ($tag) use($search) {
                    foreach($search as $sItem){
                        $tag->orwhere('title', 'like',  '%' . $sItem .'%');
                    }
                })->count();
                return $item['pesent_tag_equals'] > 0;
            }
        });
    }

    public function calculateTotalSearchIndex(Collection $courses)
    {
        return $courses->map(function($item) {
                $item['totalStringSearchIndex'] = $item['pesent_name_equals'] + $item['pesent_tag_equals'] + $item['pesent_short_description_equals'];
            return $item;
        });
    }

    

    public function hints(Request $request)
    {
        $query = $request->get('query');
        $query = collect(explode(' ', $query))->filter(fn($item) => $item !== "")->values();
        $Q = [];
        if($query->count()){
            $Q = TagSearchCourse::whereHas('courses')->where('title', 'like', '%' . $query->shift() . '%');
            $query->each(fn($part) => $Q = $Q->orWhere('title', 'like', '%' . $part . '%'));
            $Q = $Q->get()->pluck('title')->toArray();
        }

        return $Q;
    }

    public function getExceptUrlParams()
    {
        return [
            'page','sort','with_catigories', 'limit', 'search','query', 'min_price', 'max_price', 'date_min', 'date_max', 'grid'
        ];
    }

    public function checkExistsOnlyParams(Request $request, array $needleParams, $dopP)
    {
        $dopParams = array_merge($this->getExceptUrlParams(), [$dopP]);
        return $request->exists($needleParams) && count($request->except($dopParams)) === 0;
    }

}