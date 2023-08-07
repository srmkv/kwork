<?php
use \App\Http\Controllers\General\GeoController;
Route::controller(GeoController::class)->group(function() {
    Route::get('/cities', 'getCities');
    Route::get('/regions', 'getRegions');
    Route::get('/countries', 'getCountries');
    //adding
    Route::post('/cities/add', 'addCity');
    Route::post('/regions/add', 'addRegion');
    Route::post('/countries/add', 'addCountry');
});
