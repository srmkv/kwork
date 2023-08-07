<?php
use \App\Http\Controllers\Api\User\EmployeeController;

Route::controller(EmployeeController::class)->group(function(){
    //меняем статус пренадлежности компании
    Route::post('user/employee/status/{profile_id}', 'changeStatusEmployeer');
   // показать в какие компании входит сотрудник и их статус соответственно
   Route::get('user/member/companies', 'memberCompany');

   // все возможные статусы сотруднка(информативный)
   Route::get('user/employee/all-status', 'employeeStatuses');
});
