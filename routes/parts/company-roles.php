<?php
use App\Http\Controllers\Api\User\Role\RoleCompanyController;

Route::controller(RoleCompanyController::class)->group(function(){
    //создать роль
    Route::post('/company/roles/add', 'createRole');
    //редактируем роль
    Route::post('/company/roles/edit', 'editRole');
    Route::post('/company/roles/edit2', 'editRole2');
    //проверить роль
    Route::post('/company/roles/check', 'checkRole');
    // удалить роль
    Route::post('/company/roles/delete', 'deleteRole');
    //прикрепить роль сотруднику
    Route::post('/company/roles/attach', 'attachRole');
    //список ролей доступных для редактирования/просмотра (КОНКРЕТНОГО ЮР. ЛИЦА ИЛИ ИП)
    Route::get('/company/roles/list/{type}/{company_id}', 'allRolesIntoCompany');
    //
    Route::get('/company/roles/get/{role_id}', 'getRole');


    //все существующие права

    Route::get('/company/all-permissions', 'allPermissions');
});