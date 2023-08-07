<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterCategoryTag\FilterCategoryTagDeleteRequest;
use App\Http\Requests\FilterCategoryTag\FilterCategoryTagRequest;
use App\Http\Requests\FilterCategoryTag\FilterCategoryTagUpdateRequest;
use Illuminate\Http\Request;



//req
use App\Http\Resources\Course\FilterTagCategoriesResource;
use App\Models\Filter\FilterCategoryTag;

class FilterTagCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(FilterTagCategoriesResource::collection(FilterCategoryTag::with('categories')->get()), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FilterCategoryTagRequest $request)
    {
        FilterCategoryTag::create($request->all());
        return response()->json(FilterTagCategoriesResource::collection(FilterCategoryTag::all()), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FilterCategoryTagDeleteRequest $request)
    {
        return response()->json(FilterTagCategoriesResource::make(FilterCategoryTag::findOrFail($request->id)), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FilterCategoryTagUpdateRequest $request)
    {
        $filter_tag = FilterCategoryTag::findOrFail($request->id);
        $filter_tag->update($request->all());
        return response()->json(FilterTagCategoriesResource::make($filter_tag), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FilterCategoryTagDeleteRequest $request)
    {
        FilterCategoryTag::findOrFail($request->id)->delete();

        return response()->json('Тэг удалён', 201);
    }
}
