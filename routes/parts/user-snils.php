<?php
use \App\Http\Controllers\Api\User\SnilsController;

Route::controller(SnilsController::class)->group(function() {
    // создать или изменить снилс
    Route::post('user/snils', 'newSnils');
    // отобразить картинку снилса
    Route::get('user/snils/show', 'showImageSnils');
    // загрузить скан снилса
    Route::post('user/snils/image', 'loadImageSnils');
    // получить снил юзера
    Route::get('user/snils/get', 'getSnils');
    // by id
    Route::get('user/snils/get-id/{snils_id}', 'getSnilsById');

    // удалить снилс полностью
    Route::delete('user/snils/delete', 'deleteSnils');
});
