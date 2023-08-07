<?php

namespace App\Services;

use App\Models\Course\CategoryCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AdminService
{

    public function parseSortParams($sort){
        $result['field'] = 'id';
        $result['direction'] = 'asc';
    
        if($sort){
            $sortParts = explode('.',$sort);
            $result['field'] = $sortParts[0];
            $result['direction'] = $sortParts[1];
        }
    
        return $result;
    }

    public function parseSearchParams($search = null)
    {
        $result = null;

        if($search){
            $searchParts = explode('.', $search);
            $result['field'] = $searchParts[0];
            $result['part'] = $searchParts[1];
        }

        return $result;
    }
    
    /**
     * Ну тут уж простите..., из за головоломной структуры которую пожелали, 
     * пришлось писать сырой запрос, второй join решил не делать, ерунда получается
     * зато максимально быстро)
     */
    public function createSpecialityQuery()
    {
        $query = CategoryCourse::where('course_categories.tag_id', 4)
            ->leftJoin('category_course_speciality', 'course_categories.id', '=', 'category_course_speciality.category_course_id')
            ->select(
                'course_categories.id', 
                'course_categories.title', 
                'course_categories.slug', 
                'course_categories.tree'
            )
            ->addSelect(\DB::raw("JSON_EXTRACT(course_categories.tree, '$[0]') AS napravlenie_id"))
            
            ->addSelect(\DB::raw("(
                    SELECT title 
                    FROM course_categories 
                    WHERE course_categories.id = CAST(napravlenie_id AS SIGNED) 
                ) AS napravlenie"
            ))
            
            ->addSelect(\DB::raw("(
                    SELECT title 
                    FROM level_education 
                    WHERE level_education.id = category_course_speciality.level_education_id
                ) AS level_education"
            ))
        ;

        return $query;
    }

    public function paginate(Request $request, int $totalCount)
    {
        $limit = (int)$request->limit ?: config('catalog.limit');
        $page = (int)$request->page;
        $page <= 1 ? $offset = 0 : $offset = (($page - 1) * $limit);
        $per_page = intval(ceil($totalCount / $limit));

        return compact('totalCount', 'limit', 'page', 'per_page', 'offset');
    }

    public function getSpecialitiesBySort(array $sortParams = null, Collection $queryData = null)
    {
        if($sortParams && $queryData){
            $queryData = $queryData->sortBy([
                [$sortParams['field'], $sortParams['direction']]
            ]);
            return $queryData;
        }

        return collect();
    }

    public function getSpecialitiesBySearch(Collection &$specialities, array $searchParams)
    {
        $specialities = $specialities->filter(function($speciality) use($searchParams) {
            return false !== strstr(\Str::lower($speciality[$searchParams['field']]), $searchParams['part']);
        });
        return $specialities;
    }
}
