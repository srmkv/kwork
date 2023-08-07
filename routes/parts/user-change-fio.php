<?php
use \App\Http\Controllers\Api\User\ChangeNameController;

Route::controller(ChangeNameController::class)->group(function() {
    Route::post('user/changefio/img', 'showImage'); //mime
    Route::post('user/changefio/img/load', 'loadImage');
    Route::post('user/changefio/edit', 'editDocument');
    Route::get('user/changefio/info', 'infoChanges');
    Route::post('user/changefio/add', 'newDocument');
    Route::delete('user/changefio/delete', 'deleteDocument');
    Route::get('user/changefio/get-id/{fio_id}', 'infoChangesById');
});