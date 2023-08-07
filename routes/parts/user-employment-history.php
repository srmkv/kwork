<?php
use \App\Http\Controllers\Api\User\EmploymentHistoryController;

Route::controller(EmploymentHistoryController::class)->group(function() {
    // новая трудовая
    Route::post('user/workbook/create', 'newDocument');
    // получить текущую трудовую со всеми ид сканов
    Route::get('user/workbook/get', 'getDocument');
    // по ид вывести
    Route::get('user/workbook/get-id/{workbook_id}', 'getDocumentById');
    // добавить изображение в конкретную трудовую по ид
    Route::post('user/workbook/img/create/{id}', 'createImage');
    // скан трудовой
    Route::post('user/workbook/img/show', 'showImage');
    // полностью удалить документ, по ид
    Route::delete('user/workbook/delete/{id}', 'deleteDocument');

    Route::post('user/workbook/edit/{id}', 'editDocument');

    // удалить фотку
    Route::delete('/user/workbook/delete-photo/{id}', 'deletePhoto');


});
