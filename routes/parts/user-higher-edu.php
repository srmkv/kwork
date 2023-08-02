<?php
use \App\Http\Controllers\Api\User\HigherEduController;

Route::controller(HigherEduController::class)->group(function(){
    //спсиок дипломов
    Route::get('user/higher', 'diploms');
    // конкретный диплом
    Route::get('user/higher/get/{diplom_id}', 'getDiplomById');
    //добавить пустой
    Route::post('user/higher/add', 'createDiplom');
    //уровни
    Route::get('user/higher/levels', 'listLevel');
    //edit diplom
    Route::post('user/higher/edit', 'editDiplom');
    //delete
    Route::delete('user/higher/delete', 'deleteDiplom');
    // специальности вышек
    Route::get('user/higher/speciality', 'getSpeciality');
    //новая специальность
    Route::post('user/higher/speciality/add', 'newSpeciality');
    //новая специальность от юзера
    Route::post('user/higher/speciality/add', 'newSpeciality');
    //новое направление от юзера
    Route::post('user/higher/direction/add', 'newDirection');
    //направления 
    Route::get('user/higher/directions', 'getDirections');
    // специальности в конкретном направлении
    Route::get('user/higher/specialitys-in-direction/{direction_id}', 'getSpecialitysInDirection');

});