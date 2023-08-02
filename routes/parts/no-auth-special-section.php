<?php
use \App\Http\Controllers\Api\Admin\InfoEduOrganization;

// СВЕДЕНИЯ ОБ ОБР. ОРГАНИЗАЦИИ (ADMIN SPECIAL SECTION)
Route::controller(InfoEduOrganization::class)->group(function(){

    // покажем все что есть в разделе по id
    Route::get('/admin/admin-section/{id}', 'getAllFormInSpoiler');

    //все разделы (отсортированные)
    Route::get('/admin/admin-section-all', 'getSectionAll');

    // дерево со слагами
    Route::get('/admin/admin-section-tree', 'sectionTree');

    // получиьт по слагу
    Route::get('/admin/admin-section-info/{slug}', 'getTabOrSectionFromSlug');

});