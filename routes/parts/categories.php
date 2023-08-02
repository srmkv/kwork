<?php

use App\Http\Controllers\Api\Course\CategoryCourseController;
use Illuminate\Support\Facades\Route;

Route::controller(CategoryCourseController::class)->prefix('categories')->group(function () {
    Route::get('', 'categories');
});