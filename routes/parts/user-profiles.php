<?php

use \App\Http\Controllers\Api\User\ProfileIndividualController;
use \App\Http\Controllers\Api\User\ProfileBuisenessController;
use \App\Http\Controllers\Api\User\ProfileSelfEmployedController;
use \App\Http\Controllers\Api\User\Company\CompanyController;

use App\Http\Controllers\Api\User\UserAvatarController;
use App\Http\Controllers\Api\User\UserPhoneController;
use App\Http\Controllers\Api\User\UserEmailController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;

//USER  (ФИЗИКИ)
Route::post('/user/profile', [ProfileIndividualController::class, 'profile']);

//USER SELF-EMPLOYED PROFILE (ИП/САМОЗАНЯТЫЕ)
Route::controller(ProfileSelfEmployedController::class)->group(function(){
    //получить данные профиля
    Route::get('user/profile/self-employed', 'getProfile');
    //изменить данные
    Route::post('user/profile/self-employed/edit', 'editProfile');
    //загрузить лого
    Route::post('user/profile/self-employed/load-logo', 'loadLogo');
    //показать лого
    Route::get('user/profile/self-employed/show-logo', 'showLogo');

});

// ЮР ЛИЦА
Route::controller(ProfileBuisenessController::class)->group(function(){
    //создать или обновить профиль
    Route::post('/user/profile/business', 'editProfile');
    //получить профиль
    Route::get('/user/profile/business/{id}', 'getProfile');
    //загрузить лого
    Route::post('/user/profile/business/load-logo', 'loadLogo');
    //показать лого
    Route::get('/user/profile/business-show-logo', 'showLogo');
    //загрузить приказ
    Route::post('/user/profile/business/load-act', 'loadAct');
    //показать приказ
    Route::get('/user/profile/business-show-act', 'showAct');
    //виды налогообложения
    Route::get('/user/tax-types', 'taxType');
    // выдача инфы по инн
    Route::post('/user/profile/data-company-inn', 'getDataByInn');

    //добавить сотрудника
    Route::post('/company/employee/create', 'createEmployee');
    //Изменить профиль сотруднику
    Route::post('/company/edit-employee/{profile_id}', 'editEmployee');
    //пошарить в профиле сотрудника
    Route::get('/company/show-employee/{profile_id}', 'showEmployee');
    //получить всех сотрудников в компании
    Route::get('/company/list-employee/{type}/{company_id}', 'getEmployees');

});


// КОМПАНИИ TEST
Route::controller(CompanyController::class)->group(function(){
    //создать или обновить профиль
    Route::post('/company/test', 'testCompany');
});


// USER AVA
Route::controller(UserAvatarController::class)->group(function() {
    Route::get('user/avatar', 'show');
    Route::post('user/avatar', 'store');
    Route::put('user/avatar', 'update');
    Route::delete('user/avatar', 'destroy');
});


//USER PHONE
Route::controller(UserPhoneController::class)->group(function() {
    Route::post('user/phone/sms', 'changePhone');
    Route::post('user/phone/sms/resend', 'resendChangePhone');
    Route::post('user/phone/pin', 'verifyChangePhone');
});

//PASSWORD
Route::controller(PasswordController::class)->group(function() {
    Route::post('password/change', 'changePassword');
    Route::post('password/change/email', 'changeMailPassword');
    Route::post('password/change/phone', 'changeMailPassword');
});


//EMAIL USER
Route::controller(UserEmailController::class)->group(function() {
    Route::post('/email/set', 'sendVerifyMail');   
});

