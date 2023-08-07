<?php

use App\Http\Controllers\Api\CatalogController;
use Illuminate\Support\Facades\Route;

Route::controller(CatalogController::class)->prefix('catalog')->group(function () {
    Route::get('filters', 'filters');
    Route::get('', 'catalog');
    Route::get('getChildsTag', 'getChildsTag');
    Route::get('getChildsOther', 'getChildsOther');
    Route::get('hints', 'hints');
});