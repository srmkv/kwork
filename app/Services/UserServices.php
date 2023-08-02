<?php

namespace App\Services;

use App\Http\Requests\CourseRequest;
use App\Http\Resources\Course\CourseResource;
use App\Models\Course\Course;
use App\Models\User;
use Illuminate\Http\Request;

class UserServices
{
    public function lastNotPublishCourseOrNew()
    {
        if(request()->course_id){
            $course = Course::find(request()->course_id);
        }elseif($course = auth()->user()->courses()->where('is_published', 0)->orderByDesc('id')->first()){
            return CourseResource::make($course);
        }else{
            $course = Course::create(['admin_id' => auth()->user()->id]);
        }
        return CourseResource::make($course);
    }

    public function getCourses(Request $request)
    {
        if($request->ids){
            $courses = Course::whereIn('id', $request->ids)->get();
            return CourseResource::collection($courses);
        }
        return [];
    }

    public function getDocTakeImageIds()
    {
        $docTakeImageIds = collect();
        if(auth()){
            $courses = auth()->user()->courses()->with('docsTake', 'docsTake.images')->get()->toArray();
            foreach($courses as $course){
                foreach($course['docs_take'] as $docTake){
                    foreach($docTake['images'] as $image){
                        $docTakeImageIds->push($image['id']);
                    }
                }
            }
        }
        return $docTakeImageIds;
    }

}