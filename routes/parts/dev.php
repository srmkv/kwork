<?php
use App\Http\Controllers\Api\Course\DevController;
use App\Http\Controllers\General\TempController;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'dev'], function() {
    // временно, проверка наличия токенов при регистрации
    Route::get('check-token', [DevController::class, 'checkToken']);
    Route::post('change-phone', [DevController::class, 'changePhone']); // ?
    Route::post('clear-redis', [DevController::class, 'clearRedis']);
    Route::post('clear-cache', [DevController::class, 'clearCache']);
    Route::get('all-redis-keys', [DevController::class, 'getAllRedisKeys']);
    Route::post('redis-create-catalog', [DevController::class, 'redisCreateCatalog']);
    Route::post('clear-all-courses', [DevController::class, 'clearAllCourses']);
    Route::post('reset-categories-parents', [DevController::class, 'resetCategoriesParents']);
    Route::post('catalog-update-prices', [DevController::class, 'updateMinMaxPrices']);
    Route::post('clear-nullable-courses', [DevController::class, 'clearNullableStatusCourses']);
});



Route::controller(TempController::class)->group(function () {
    //Роуты закоменчены, чтобы случайно не вызвать повторно 
    // Route::get('parse/residency', 'insertResidencySpeciality');
    // Route::get('parse/specialitet', 'insertSpecialitetSpeciality');
    // Route::get('parse/postgraduate', 'insertPostgraduateSpeciality'); // аспирантура
    // Route::get('parse/assistant', 'insertAssistantIntershipSpeciality'); // ассистентура-стажировка
    // Route::get('parse/backelor', 'insertBackelorSpeciality'); 
    // Route::get('parse/master', 'insertMasterSpeciality'); 

    Route::post('/dev/any-tests', 'anyTests');

});


