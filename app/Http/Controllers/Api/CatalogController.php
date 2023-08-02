<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogCategoryChildsRequest;
use App\Http\Requests\CatalogCourseRequest;
use App\Http\Requests\CatalogSectionRequest;
use App\Http\Requests\CatalogFilterRequest;
use App\Http\Resources\Course\CategoryCourseResource;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Course\MenuResource;
use App\Http\Resources\FilterCategoryTagResource;
use App\Models\Course\CategoryCourse;
use App\Models\Course\Course;
use App\Models\Course\DateStudy;
use App\Models\Filter\FilterCategoryTag;
use App\Services\CourseService;
use App\Services\CatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class CatalogController extends Controller
{
    
    private $catalogService;
    private $courseSrvice;

    public function __construct(CatalogService $catalogService, CourseService $courseSrvice)
    {
        $this->catalogService = $catalogService;
        $this->courseSrvice = $courseSrvice;
    }

    public function catalog(CatalogCourseRequest $request)
    {
        
        $this->catalogService->clearCache();
        $exceptUrlParams = $this->catalogService->getExceptUrlParams();
        
        // Временная "фича", из за особенностей Redis если попадаются в запросе фильтры даты или цены, для выдачи max/min дат и цен
        // для каждой из них делаем свой запрос в Redis для расчёта, но без самого этого фильтра (на нагрузку БД не влияет!)
        if ($request->exists('price') || $request->exists('date')) {
            if ($request->exists('price')) {
                $exceptUrlParams = array_merge($this->catalogService->getExceptUrlParams(),['price']);
                $redisKeys = $this->catalogService->parseUrlParams($request->except($exceptUrlParams));
                $catalog = $this->catalogService->catalog($request, $redisKeys);
                $dataForPrice = $this->catalogService->getCourses($catalog);
            }
            if($request->exists('date')){
                $exceptUrlParams = array_merge($this->catalogService->getExceptUrlParams(),['date']);
                $redisKeys = $this->catalogService->parseUrlParams($request->except($exceptUrlParams));
                $catalog = $this->catalogService->catalog($request, $redisKeys);
                $dataForDate = $this->catalogService->getCourses($catalog);
            }
        }else{
        }
        /**
         * ["napravlenie_it","napravlenie_po"]
         * ["podnapravlenie_draivera"]
         */
        $redisKeys = $this->catalogService->parseUrlParams($request->except($this->catalogService->getExceptUrlParams()));
        $catalog = $this->catalogService->catalog($request, $redisKeys);


        $totalCount = 0;
        $courses = $this->catalogService->getCourses($catalog);
        
        $search = $request->get('query');
        $search = collect(explode(' ', $search))->filter(fn($item) => $item !== "")->values();   
        if($courses){
            $totalCount = $courses->count();
            if($search->count()){
                $coursesByTitle = $this->catalogService->calculateTotalSearchIndex($this->catalogService->searchByStringValues($courses, $search, 'name'));
                $coursesByTag = $this->catalogService->calculateTotalSearchIndex($this->catalogService->searchByTag($courses, $search, $request->get('query')));
                $coursesByDescription = $this->catalogService->calculateTotalSearchIndex($this->catalogService->searchByStringValues($courses, $search, 'short_description'));
                $courses = collect();
                $courses = $courses->merge($coursesByTitle)->merge($coursesByTag)->merge($coursesByDescription)->unique('id');
                $courses = $courses->sortByDesc('totalStringSearchIndex')->slice($catalog['offset'])->take($catalog['limit']);
                
            }else{
                $courses = $courses->slice($catalog['offset'])->take($catalog['limit']);
            }
        }
        // 1679091c5a880faf6fb5e6087eb1b2dc
        $prefixSlugs = md5(join(',',  $catalog['data']));

        Cache::remember(CatalogService::CACHE_CHECKED_CATEGORIES_SLUGS . $prefixSlugs, config('cache.ttl-catalog-main'), function () use($courses) {
            if(!$courses){   
                return null;   
            }
            $result = collect();
            $result = $result->merge($courses->pluck('categoryCourse')->collapse()->pluck('slug', 'id')->unique())
                ->merge($courses->pluck('duration')->collapse()->pluck('slug', 'id')->unique())
                ->merge($courses->pluck('addresses')->collapse()->pluck('slug', 'id')->unique())
                ->values()->toArray()
            ;
            return $result;
        });

        Cache::remember(CatalogService::CACHE_ALL_CATEGORIES_SLUGS, config('cache.ttl-catalog-main'), function () {   
            $courses = Course::active()->withCategoryCourse()->withDuration()->withAddresses()->get();
            if(!$courses){   
                return null;   
            }
            $result = collect();
            $result = $result->merge($courses->pluck('categoryCourse')->collapse()->pluck('slug', 'id')->unique())
                ->merge($courses->pluck('duration')->collapse()->pluck('slug', 'id')->unique())
                ->merge($courses->pluck('addresses')->collapse()->pluck('slug', 'id')->unique())
                ->values()->toArray()
            ;
            return $result;
        });

        if($courses && $courses->count()){
            $courses = CourseResource::collection($courses);
    
            Cache::remember(CatalogService::CACHE_COURSE_CATEGORIES_SLUGS_REMEMBER_PREFIX . 
                $catalog[CatalogService::CACHE_COURSE_CATEGORIES_SLUGS_HASH_NAME], config('cache.ttl-catalog-main'), function () use($courses) {
                    if(!$courses){   
                        return null;   
                    }
                    return $courses->pluck('categoryCourse')->collapse()->pluck('slug', 'id')->unique();
            });
        }

        $result = [
            'totalCount' => $totalCount,
            'limit' => $catalog['limit'],
            'per_page' => $catalog['per_page'],
            'page' => $catalog['page'],
            'sort' => join('.',$catalog['sort']),
            'min_price' => isset($dataForPrice) ? $dataForPrice->min('min_price') : ($courses ? $courses->min('min_price') : null),
            'max_price' => isset($dataForPrice) ? $dataForPrice->max('max_price') : ($courses ? $courses->max('max_price') : null),   
            'date_min' => isset($dataForDate) ? $dataForDate->min('date_min') : ($courses ? $courses->min('date_min') : null),
            'date_max' => isset($dataForDate) ? $dataForDate->max('date_max') : ($courses ? $courses->max('date_max') : null),
            'all_start_dates' => DateStudy::all(['start'])->sort()->pluck('start')->toArray(),
            
            'courses' => $courses ?? [],
        ];

        return response()->json($result, 200);
    }

    public function filters(CatalogCourseRequest $request)
    {
        $filters = $this->catalogService->filters($request);
        return response()->json($filters, 200);
    }

    public function hints(Request $request)
    {
        $hints = $this->catalogService->hints($request);
        return response()->json($hints, 200);
    }

    public function getChildsTag(CatalogCategoryChildsRequest $request)
    {
        return CategoryCourse::where('slug', $request->slug)->first()->childrenWithSelfTag;
    }

}
