<?php
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\User\UserEmailController;
use App\Http\Controllers\Auth\AdminLoginController;

// REGISTER
Route::controller(RegisterController::class)->group(function() {
    Route::post('/register/sms', 'send');
    Route::post('/register/pin', 'verifiedPin');
    Route::post('/register/password' , 'setPassword');
    Route::post('/register/pin/phone' , 'verifyRecoverPhone');
});

//LOGIN
Route::post('/login', [LoginController::class, 'login']);
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::post('/login/checkphone', [RegisterController::class, 'checkPhone']);
Route::get('/logout', [LoginController::class, 'logout']);

//MAIL VERIFY
Route::get('/email/pin/verify', [UserEmailController::class, 'veryfyPinMail']);



