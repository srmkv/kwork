<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\Api\Admin\AdminController;


Route::middleware('role')->group(function(){
    Route::controller(AdminController::class)->group(function() {
        Route::get('/admin/dashboard', 'dashboard');
    });
});

// NO AUTH GROUP
require_once(__DIR__ . '/parts/not-auth-courses.php');
require_once(__DIR__ . '/parts/no-auth-special-section.php');
require_once(__DIR__ . '/parts/dev.php');
require_once(__DIR__ . '/parts/catalog.php');
require_once(__DIR__ . '/parts/register.php');
require_once(__DIR__ . '/parts/callback.php');
require_once(__DIR__ . '/parts/categories.php');
require_once(__DIR__ . '/parts/menu.php');


//AUTH (USER)
Route::middleware(['auth:sanctum', 'dev_token'])->group(function () {
    require_once(__DIR__ . '/parts/auth-courses.php');
    require_once(__DIR__ . '/parts/general-media.php');
    require_once(__DIR__ . '/parts/general-geo.php');
    require_once(__DIR__ . '/parts/general-edu.php');
    require_once(__DIR__ . '/parts/chat.php');
    require_once(__DIR__ . '/parts/user-employee.php');
    require_once(__DIR__ . '/parts/user-dashboard.php');

    //мои документы
    require_once(__DIR__ . '/parts/user-higher-edu.php');
    require_once(__DIR__ . '/parts/user-secondary-edu.php');
    require_once(__DIR__ . '/parts/user-specialized-secondary-edu.php');
    require_once(__DIR__ . '/parts/user-additional-edu.php');
    require_once(__DIR__ . '/parts/user-passports.php');
    require_once(__DIR__ . '/parts/user-snils.php');
    require_once(__DIR__ . '/parts/user-change-fio.php');
    require_once(__DIR__ . '/parts/user-employment-history.php');
    require_once(__DIR__ . '/parts/user-other-documents.php');

    // профили
    require_once(__DIR__ . '/parts/user-profiles.php');

    // роли/права
    require_once(__DIR__ . '/parts/company-roles.php');
    require_once(__DIR__ . '/parts/admin-roles.php');
    require_once(__DIR__ . '/parts/admin-info-edu-organization.php');
    
    // настройка главной
    require_once(__DIR__ . '/admin_parts/setting-main-page.php');

    //заявки на курс
    require_once(__DIR__ . '/parts/orders.php' );
    //оплаты
    require_once(__DIR__ . '/parts/payments.php' );

    // сокеты
    require(__DIR__. '/parts/websockets.php');

    // админка
    require(__DIR__. '/parts/admin.php');
});


