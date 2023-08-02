<?php
//MEDIA
use \App\Http\Controllers\Api\Media\MediaItemController;

Route::controller(MediaItemController::class)->group(function() {
    // удаление (юзерский роут)
    Route::delete('/media/delete', 'deleteMedia');
    // загрузка картинок/pdf
    Route::post('/media/load', 'loadMedia');
    // общий список коллекций, для фронта 
    Route::get('/media/collection', 'getNameCollectionsMedia'); 
    // список изображений для конкретного юзера, конкретной коллекции
    Route::get('/media/collection/specific', 'findMediaInCollection');
    //получить изображение по id ( только для админов) 
    Route::get('/media/image', 'imageShow');
    //получить изображение по id ( для юзеров) 
    Route::get('/media/image/show-for-user', 'imageShowForUser');
    //удаление для админов (любой медиа_ид)
    Route::delete('/media/admin/delete/{media_id}', 'MediaDeleteForAdmin');


    //общее - урл для медиа итема
    // Route::get('/media/show-url-media/{media_id}', 'showUrlMedia');
    Route::post('/media/show-url-media', 'showUrlMedia');


    // получить все файлы конкретной модели, для админа
    Route::post('/media/admin/collection-get', 'getCollectionMedia');
});
