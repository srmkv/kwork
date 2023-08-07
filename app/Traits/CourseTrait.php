<?php
namespace App\Traits;

use App\Models\User;
use Illuminate\Http\Request;

use App\Models\Course\Course;
use App\Models\Course\CategoryCourse;


trait CourseTrait
{   
    public static function getReceivedDocuments($course_id)
    {
    	return \DB::table('course_docs_take')->where('course_id', $course_id)->get();
    }

    public static function getSpeciality($course_id)
    {   
        $tree = Course::find($course_id)->tree;
        $specialities = CategoryCourse::where('tag_id', 4)->pluck('id')->toArray();

        // foreach ($tree as $categories) {
        //     $interSect = array_intersect($specialities, $categories);
        //     if(count($interSect) > 0) {
        //         return CategoryCourse::find(array_shift($interSect))->title ?? '';
        //     } 
        // }

        $categories = CategoryCourse::whereIn('id', $tree)->get();
        $categoryIds = $categories->pluck('id');
        // Получаем пересечение специальностей и идентификаторов категорий
        $intersectedIds = $specialities->intersect($categoryIds);
        // Ищем первую категорию, которая соответствует пересечению
        $category = $categories->first(function ($category) use ($intersectedIds) {
            return $intersectedIds->contains($category->id);
        });
        return optional($category)->title ?? '';


    }

    public static function getDirection($course_id)
    {
       $tree = Course::find($course_id)->tree;
       $specialities = CategoryCourse::where('tag_id', 1)->pluck('id')->toArray();

       // foreach ($tree as $categories) {
       //     $interSect = array_intersect($specialities, $categories);
       //     if(count($interSect) > 0) {
       //         return CategoryCourse::find($interSect[0])->title;
       //     } 
       // } 

       $categories = CategoryCourse::whereIn('id', $tree)->get();
       $categoryIds = $categories->pluck('id');
       $intersectedIds = $specialities->intersect($categoryIds);
       $category = $categories->first(function ($category) use ($intersectedIds) {
           return $intersectedIds->contains($category->id);
       });
       return optional($category)->title ?? '';
    }

    public static function needChangeSurname($course_id)
    {
        return Course::find($course_id)->is_change_surname ? true : false;
    }
}

