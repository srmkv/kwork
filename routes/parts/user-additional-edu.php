<?php
use \App\Http\Controllers\Api\User\AdditionalEduController;

Route::controller(AdditionalEduController::class)->group(function(){
    //спсиок доп. образоавний юзера
    Route::get('user/edu/additional', 'getFormations');
    // доп. диплом по ид
    Route::get('user/edu/additional/get/{diplom_id}', 'getFormationById');
    //добавить пустое доп. обр.
    Route::post('user/edu/additioanal/add', 'createFormation');
    //изменить доп. образование
    Route::post('user/edu/additional/edit', 'editFormation');
    //новая специальность от юзера
    Route::post('user/edu/additional/specialities/add', 'newSpeciality');
    // вывести все специальности для доп. образоавния
    Route::get('user/edu/additional/specialities', 'getAllSpecialities');
    //специальность по id
    Route::get('user/edu/additional/specialities/{speciality_id}', 'getSpeciality');
    //новое направление от юзера
    Route::post('user/edu/additional/direction/add', 'newDirection');
    // вывести все направления для доп. образоавния
    Route::get('user/edu/additional/directions', 'getAllDirections');
    // направление по id
    Route::get('user/edu/additional/directions/{speciality_id}', 'getSpeciality');
    //специальности в направлении 
    Route::get('user/edu/additional/specialities/direction/{direction_id}', 'getSpecialitiesInDirection');
    //удалить доп. образование
    Route::delete('user/edu/additional/delete', 'deleteFormation');

});