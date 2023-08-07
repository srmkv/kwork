<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Http\Requests\SpecialityStoreRequest;

//resources
use App\Http\Resources\Course\SpecialityResource;


//models
use App\Models\Course\Speciality;
use App\Models\Course\LevelEducation;



class SpecialityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        return SpecialityResource::collection(Speciality::with(
            'courses',
            'levelEducation'
        )->get());
    }




    // ищем spec
    public function search($title)
    {   
        $result = Speciality::where('title', 'LIKE', '%'. $title. '%')->get();
        if(count($result)){
         return Response()->json($result);
        }else{
          return response()->json(['Result' => 'No Data not found'], 404);
        }
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SpecialityStoreRequest $request)
    {
        $speciality = Speciality::create($request->all());
        return response()->json(SpecialityResource::make($speciality), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new SpecialityResource(Speciality::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SpecialityStoreRequest $request, $id)
    {   
        $speciality = Speciality::findOrFail($id);
        $speciality->update($request->validated());   

        return new SpecialityResource($speciality);
    }






    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Speciality::findOrFail($id)->delete();
        return 'delete success';
    }
}
