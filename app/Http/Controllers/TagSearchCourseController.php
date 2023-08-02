<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagSearch\TagSearchCourseDeleteRequest;
use App\Http\Requests\TagSearch\TagSearchCourseRequest;
use App\Http\Requests\TagSearch\TagSearchCourseUpdateRequest;
use App\Http\Resources\TagSearchCourseResource;
use App\Models\Course\TagSearchCourse;

class TagSearchCourseController extends Controller
{
    public function index()
    {
        return response()->json(TagSearchCourseResource::collection(TagSearchCourse::all()), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagSearchCourseRequest $request)
    {
        return response()->json(TagSearchCourseResource::make(TagSearchCourse::create($request->all())), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TagSearchCourseDeleteRequest $request)
    {
        return response()->json(TagSearchCourseResource::make(TagSearchCourse::findOrFail($request->id)), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TagSearchCourseUpdateRequest $request)
    {
        $filter_tag = TagSearchCourse::findOrFail($request->id);
        $filter_tag->update($request->all());
        return response()->json(TagSearchCourseResource::make($filter_tag), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TagSearchCourseDeleteRequest $request)
    {
        TagSearchCourse::findOrFail($request->id)->delete();

        return response()->json('Тэг удалён', 201);
    }
}
