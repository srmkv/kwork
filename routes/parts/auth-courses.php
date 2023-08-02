<?php
use App\Http\Controllers\Api\Course\BannerController;
use App\Http\Controllers\Api\Course\CategoryCourseController;
use App\Http\Controllers\Api\Course\CourseController;
use App\Http\Controllers\Api\Course\PersonalDocController;
use App\Http\Controllers\Api\Course\NeedOtherTypesController;
use App\Http\Controllers\Api\Course\CourseReviewController;
use App\Http\Controllers\Api\Course\CourseSectionController;
use App\Http\Controllers\Api\Course\CourseSectionThemeController;
use App\Http\Controllers\Api\Course\CourseStatistics;
use App\Http\Controllers\Api\Course\FlowController;
use App\Http\Controllers\Api\Course\PacketController;
use App\Http\Controllers\Api\Course\PacketDescriptionController;
use App\Http\Controllers\Api\Course\PacketSaleRuleController;
use App\Http\Controllers\Api\Course\TagRefinementController;
use App\Http\Controllers\Api\Course\TeacherController;
use App\Http\Controllers\Api\Course\PacketInstallmentController;
use App\Http\Controllers\Api\Course\CourseSectionLessonController;
use App\Http\Controllers\Api\Course\LevelEducationController;
use App\Http\Controllers\Api\Course\VacantPlaceCourseController;

use App\Http\Controllers\Api\FilterTagCategoryController;
use App\Http\Controllers\TagSearchCourseController;
use Illuminate\Support\Facades\Route;

// Vacant Places
Route::controller(VacantPlaceCourseController::class)->prefix('special-course')->group(function(){
    Route::post('vacant-places/edu-program', 'editPlaceForEduProgram');
    Route::post('vacant-places/speciality', 'editPlaceForSpeciality');
    Route::post('vacant-places/direction', 'editPlaceForDirection');
    Route::post('vacant-places/profession', 'editPlaceForProfession');
    Route::post('profession/create', 'createProfession');
    Route::get('profession/all', 'getProfessions');
});

/**
 * Тэги уточнения
 */
Route::controller(TagRefinementController::class)->prefix('tagref')->group(function () {
    Route::get('', 'index');
    Route::get('show', 'show');
    Route::post('', 'store');
    Route::put('', 'update');
    Route::delete('', 'destroy');
});

//Личные документы в курсе
Route::controller(PersonalDocController::class)->group(function() {
    Route::post('/course-docs/personal/create', 'createPersonalDocCourse');
    Route::post('/course-docs/personal/edit', 'editPersonalDocCourse');
    Route::get('/course-docs/personal/{course_id}', 'getPersonalDocsCourse');
    Route::get('/course-docs/personal-all-type', 'personaDocsAllTypes');
    Route::delete('/course-docs/personal/delete/{document_id}', 'deletePersonalDocCourse');
});

// Доп. документы в курсе
Route::controller(NeedOtherTypesController::class)->group(function() {
    Route::post('/course-docs/other-type/create', 'createNeedOtherType');
    Route::post('/course-docs/other-type/edit', 'editNeedOtherType');
    Route::get('/course-docs/other-type/{course_id}', 'getOtherTypesCourse');
    Route::delete('/course-docs/other-type/{document_id}', 'deleteOtherTypesCourse');
});

// Рассрочка для пакета
Route::controller(PacketInstallmentController::class)->group(function(){
    // новая рассрочка
    Route::post('/packet-installment/create', 'createInstallment');
    // изменить конкретную рассрочку
    Route::post('/packet-installment/edit', 'editInstallment');
    // получить рассрочки внутри пакета
    Route::get('/packet-installment/get/{packet_id}', 'getInstallments');
    // получить все типы рассрочек тинькоф (не актуально)
    Route::get('packet-installment/get-tinkoff-type', 'getTinkoffInstallments');
    // можно ли платить тинькофф рассрочкой? да/нет
    Route::post('packet-installment/tinkoff-enable/{packet_id}/{state}', 'editStateTinkoffInstallment');
    // удалить рассрочку
    Route::delete('packet-installment/delete/{installment_id}', 'deleteInstallment');
    // разделить платеж (для юр. лиц)
    Route::post('packet-split/edit/{packet_id}', 'editPacketSplitForBusiness');
    // получиьт разделенные платежи
    Route::get('packet-split/get/{packet_id}', 'getPacketSplitForBusiness');
});

// Banners
Route::controller(BannerController::class)->prefix('banners')->group(function () {
    Route::get('', 'index');
    Route::get('{banner}', 'show');
    Route::post('', 'store');
    Route::put('{banner}', 'update');
    Route::delete('', 'destroy');
    Route::post('deleteMedia', 'deleteMedia');
});

//COURSES
Route::controller(CourseController::class)->prefix('courses')->group(function () {
    Route::get('likes', 'likes');
    Route::post('', 'store');
    Route::get('info', 'info');
    Route::post('addPictureEditor', 'addPictureEditor');
    Route::get('getPictureEditor', 'getPictureEditor');
    Route::post('deleteReqDoc', 'deleteRequiredDocument');
    Route::post('deleteReqEduDoc', 'deleteRequiredEduDocument');
    Route::post('deleteCourseDocImages', 'deleteCourseDocImages');
    Route::get('filters', 'filters');
    Route::get('catalog', 'catalog');
    Route::post('process', 'process');
    Route::post('video', 'addVideo');
    Route::delete('video', 'deleteVideo');

    Route::post('preview', 'addPreview');
    Route::delete('preview', 'deletePreview');
    
    Route::group(['prefix' => 'docTake'], function(){
        Route::post('', 'addDocTake');
        Route::delete('', 'deleteDocTake');
        Route::delete('image', 'deleteDocTakeImage');
    });



    Route::get('{course}', 'show'); // Временно
    Route::get('{course}/bid_info', 'bidInfo');
    Route::post('{course}/flow', 'addFlow');
    Route::post('{course}/like', 'like');
    Route::post('{course}/whsDelete', 'whsDelete');
    Route::post('{course}/utpDelete', 'utpDelete');

    Route::post('{course}/addStudyPlanImages', 'addStudyPlanImages');

    Route::post('{course}/addCalendarStudyScheduleImages', 'addCalendarStudyScheduleImages');

    Route::post('{course}/addSpecDocImages', 'addSpecDocImages');

    Route::post('{course}/addStudyDocs', 'addStudyDocs');
    Route::post('{course}/deleteStudyDoc', 'deleteStudyDoc');

    Route::post('{course}/faqDelete', 'destroyFaq');
    Route::post('{course}/faqDuestionDelete', 'destroyFaqQuestion');
    Route::post('{course}/faqAnswerDelete', 'destroyFaqAnswer');

    Route::controller(FlowController::class)->prefix('flows')->group(function () {
        Route::post('delete', 'delete');
    });

    Route::controller(CourseSectionController::class)->prefix('sections')->group(function () {
        Route::post('delete', 'delete');
        
        Route::controller(CourseSectionLessonController::class)->prefix('lessons')->group(function () {
            Route::delete('delete', 'delete');
            Route::post('teacherDelete', 'teacherDelete');
    
            Route::controller(CourseSectionThemeController::class)->prefix('themes')->group(function () {
                Route::post('delete', 'delete');
            });
        });
        
    });

    Route::controller(PacketController::class)->prefix('packets')->group(function () {
        Route::post('delete', 'delete');

        Route::controller(PacketDescriptionController::class)->prefix('descriptions')->group(function () {
            Route::post('delete', 'delete');
        });

        Route::controller(PacketSaleRuleController::class)->prefix('saleRules')->group(function () {
            Route::post('delete', 'delete');
        });

    });

    Route::controller(CourseReviewController::class)->prefix('reviews')->group(function () {
        Route::post('', 'store');
        Route::put('', 'update');
        Route::delete('', 'destroy');
        Route::post('publish', 'store');
    });

});

Route::controller(TeacherController::class)->prefix('teachers')->group(function () {
    Route::get('', 'index');
    Route::get('{teacher}', 'show');
    Route::post('', 'store');
    Route::put('{teacher}', 'update');
    Route::delete('{teacher}', 'destroy');
});

Route::controller(LevelEducationController::class)->prefix('level_educations')->group(function () {
    Route::get('', 'index');
});



Route::controller(CategoryCourseController::class)->prefix('course_categories')->group(function () {
    Route::get('', 'index');
    Route::get('list', 'list');
    Route::get('all', 'all');
    Route::post('', 'store');
    Route::put('', 'update');
    Route::delete('', 'destroy');
    Route::post('addTag', 'addTag');
    Route::post('get-tree', 'getTree');
    Route::delete('faqDelete', 'destroyFaq');
    Route::delete('faqQuestionDelete', 'destroyFaqQuestion');
    Route::delete('faqAnswerDelete', 'destroyFaqAnswer');
    Route::get('speciality', 'speciality');
    Route::post('addImage', 'addImage');
    Route::post('deleteImage', 'deleteImage');
});

Route::controller(FilterTagCategoryController::class)->prefix('category_filter_tags')->group(function () {
    Route::get('', 'index');
    Route::get('show', 'show');
    Route::post('', 'store');
    Route::put('', 'update');
    Route::delete('', 'destroy');
});

Route::controller(TagSearchCourseController::class)->prefix('search_tags')->group(function () {
    Route::get('', 'index');
    Route::get('show', 'show');
    Route::post('', 'store');
    Route::put('', 'update');
    Route::delete('', 'destroy');
});

// статистика по конкретному курсу
Route::post('courses-statistics/{course_id}', [CourseStatistics::class,'check'])->name('course-categories.packets');       