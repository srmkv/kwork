<?php
use \App\Http\Controllers\Api\User\SpecializedSecondaryEduController;

Route::controller(SpecializedSecondaryEduController::class)->group(function(){
    //спсиок школ
    Route::get('user/edu/specialized-secondary', 'getSchools');
    // by id
    Route::get('user/edu/specialized-secondary/get/{school_id}', 'getSchoolById');
    //добавить пустую школу
    Route::post('user/edu/specialized-secondary/add', 'createSchool');
    //edit school
    Route::post('user/edu/secondary/specialized-secondary/edit', 'editSchool');
    //удалить образование
    Route::delete('user/edu/secondary/specialized-secondary/delete', 'deleteSchool');
    //Все среднетехнич. специальности с их направлениями
    Route::get('user/edu/secondary/specialized-secondary/speciality', 'getSpeciality');
    // Новая специальность от юзера
    Route::post('user/edu/secondary/specialized-secondary/speciality/add', 'newSpeciality');
    // новое направление от юзера
    Route::post('user/edu/secondary/specialized-secondary/direction/add', 'newDirection');
    // все направления
    Route::get('user/edu/secondary/specialized-secondary/directions', 'getDirections');
    // спецухи в конкретном направлении
    Route::get('user/edu/secondary/specialized-secondary/specialitys-in-direction/{direction_id}', 'getSpecialitysInDirection');
});