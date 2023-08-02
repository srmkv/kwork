<?php

use App\Http\Controllers\Api\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::controller(AdminController::class)->prefix('admin')->group(function(){
    Route::get('specialities', 'index');
});