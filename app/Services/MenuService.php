<?php

namespace App\Services;

use App\Models\Course\CategoryCourse;

class MenuService
{
    public function menu()
    {
        $result = CategoryCourse::withParents()->active()->whereDoesntHave('parent');
        return $result;
    }

    public function menuSection(int $id)
    {
        $studyTypes = CategoryCourse::studyTypes()->get(['id', 'title', 'slug','main_parent_ids'])->unique('slug')
            ->map(function($studyType)use($id){
                $ids = json_decode($studyType->main_parent_ids);
                $studyType['categories'] = CategoryCourse::where('tag_id', $id)->byStudyTypes($ids)->get(['id', 'title', 'slug', 'tree'])->map(function($category){
                    $tree = json_decode($category['tree']);
                    $category['uri_params']; 
                });
                return $studyType;
            }
        )->toArray();

        return $studyTypes;
    }

}