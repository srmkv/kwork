<?php
use App\Http\Controllers\Api\User\Role\RoleAdminController;

Route::controller(RoleAdminController::class)->group(function(){
    //создать роль
    Route::post('/admin/roles/add', 'createRole');
    //редактируем роль
    Route::post('/admin/roles/edit', 'editRole');
    //проверить роль
    // Route::post('/admin/roles/check', 'checkRole');
    // удалить роль
    Route::post('/admin/roles/delete', 'deleteRole');
    //прикрепить роль сотруднику
    // Route::post('/company/roles/attach', 'attachRole');
    //список ролей доступных для редактирования/просмотра
    Route::get('/admin/roles/list-available', 'listRolesAvailable');

});
