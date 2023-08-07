<?php

namespace App\Http\Controllers\General;


//models
use App\Models\General\Country;
use App\Models\General\City;
use App\Models\General\Region;

//resource
use App\Http\Resources\General\CountryResource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeoController extends Controller
{
    public function getCountries(Request $request)
    {
        $countries = Country::all();

        return CountryResource::collection($countries);
    }


    public function getCities(Request $request)
    {
        $cities = City::all();

        return collect($cities);
    }


    public function getRegions(Request $request)
    {
        $regions = Region::all();

        return collect($regions);
    }






    //ADDING
    //добавить проверку, на существование города/региона в базе вк
    public function addCity(Request $request)
    {

        if (City::where('city_id', '=', $request->city_id)->exists()) {
           
           return response()->json([
               "message" => "Город уже существует..",
               "code" => 202,
           ],202);
        }



        $city = new City;

        $city->title = $request->title;
        $city->title_ru = $request->title_ru;
        $city->title_en = $request->title_en;
        $city->country_id = $request->country_id;
        $city->region_id = $request->region_id ?? null;
        $city->city_id = $request->city_id;


        $city->save();




        if($city){

            return response()->json([
                "message" => "Добавлен новый город..",
                "code" => 201,
                "city_id" => $city->id
                
            ],201);

        } else {

            return response()->json([
                "message" => "Нельзя добавить новый город..",
                "code" => 403,
            ],403);
        }

    }


    public function addRegion(Request $request)
    {

        $region = new Region;

        $region->title_ru = $request->title_ru;
        $region->title_en = $request->title_en;

        $region->country_id = $request->country_id;
        $region->region_id = $request->region_id;


        $region->save();

        if($region){

            return response()->json([
                "message" => "Добавлена новая область(район)",
                "code" => 201,
                "city_id" => $region->id
                
            ],201);

        } else {

            return response()->json([
                "message" => "Нельзя добавить новый регион..",
                "code" => 403,
            ],403);
        }

    }








}
