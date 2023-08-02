<?php
use \App\Http\Controllers\Api\User\PassportController;

Route::controller(PassportController::class)->group(function() {
    Route::post('user/passport/img', 'showPassport');
    // Прикрепить image паспорта
    Route::post('user/passport/img/load', 'LoadImg');
    // media паспортов юзера
    Route::get('user/passport/info', 'MediaInfoPassports');
    // основное инфо
    Route::get('user/passport', 'mainInfoPassports');
    // empty паспорт
    Route::post('user/passport/add', 'addPassport');
    //edit
    Route::post('user/passport/edit', 'editPasport');
    //delete
    Route::delete('user/passport/delete', 'deletePasport');
    // by id
    Route::get('user/passport/get/{passport_id}', 'passportById');
});