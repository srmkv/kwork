<?php
use \App\Http\Controllers\General\EduController;

Route::controller(EduController::class)->group(function() {
    Route::get('/edu/directions', 'getDirections');
    Route::get('/edu/study_forms', 'getStudyForms');

    Route::post('/edu/filter-all', 'getHigherSpecific');
    Route::post('/edu/filter-directions-all', 'getDirectionsSpecific');
    Route::post('/edu/create-edu-doc', 'createEduDocCourse');
    Route::post('/edu/edit-edu-doc', 'editEduDocCourse');
    Route::delete('/edu/delete-edu-doc', 'deleteEduDocCourse');

    //получить образоавтельный документ из курса по ид
    Route::get('/edu/get-edu-doc/{document_id}', 'getDocumentEdu');
    // получить все в курсе
    Route::get('/edu/get-edu-doc-all/{course_id}', 'getDocumenstEduIntoCourse');

    //ПОЛУЧИТЬ ВООБЩЕ ВСЕ В КУРСЕ НЕОБХОДИМЫЕ ДОКУМЕНТЫ
    Route::get('/edu/need-doc-all/{course_id}', 'getAllNeedDocuments');


    //вроде не актуальные..
    Route::post('/edu/attach-study-docs-course', 'attachStudyDocsCourse');
    Route::get('/edu/attach-study-docs/{course_id}', 'getCourseStudyDocs');


});