<?php
use \App\Http\Controllers\Api\User\SecondaryEduController;

Route::controller(SecondaryEduController::class)->group(function(){
    // спсиок школ
    Route::get('user/edu/secondary', 'getSchools');
    // школа по ид
    Route::get('user/edu/secondary/get/{school_id}', 'getSchoolById');
    //добавить пустую школу
    Route::post('user/edu/secondary/add', 'createSchool');
    //edit school
    Route::post('user/edu/secondary/edit', 'editSchool');
    //удалить школу
    Route::delete('user/edu/secondary/delete', 'deleteSchool');
});