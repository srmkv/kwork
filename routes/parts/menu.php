<?php

use App\Http\Controllers\Api\Course\MenuController;
use Illuminate\Support\Facades\Route;

Route::controller(MenuController::class)->prefix('menu')->group(function(){
    Route::get('', 'index');
    Route::get('section', 'section');
});