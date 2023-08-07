<?php
use \App\Http\Controllers\Api\User\OtherDocumentsController;

Route::controller(OtherDocumentsController::class)->group(function() {
     //получим все доступные типы прошедшие модерацию
     Route::get('user/otherdocs/moderated-all', 'moderatedTypes'); 
     //Новый доп. документ NULL
     Route::post('user/otherdocs/add', 'newDocument');
     //по ид вывести
     Route::get('user/otherdocs/get-id/{other_doc_id}', 'getOtherDocById');
     // Изменить доп. документ
     Route::post('user/otherdocs/edit', 'editDocument');
     // получить все доп. документы пользователя
     Route::get('user/otherdocs/get', 'getOtherDocuments'); 
     //mime pic
     Route::post('user/otherdocs/img', 'showImages');
     //load img
     Route::post('user/otherdocs/img/load', 'loadFiles'); 
     //delete
     Route::delete('user/otherdocs/delete', 'deleteOtherDocument');
     // Новый тип
     Route::post('/user/otherdocs/create-type', 'newTypeDocument');
     // Изменить тип
     Route::post('/user/otherdocs/edit-type/{type_id}', 'editTypeDocument');
     // Удалить тип
     Route::delete('/user/otherdocs/delete-type/{type_id}', 'deleteTypeDocument');
});
