<?php

use \App\Http\Controllers\Api\Admin\SettingMainPage\LogoController;
use \App\Http\Controllers\Api\Admin\SettingMainPage\HeaderMenuController;
use \App\Http\Controllers\Api\Admin\SettingMainPage\ContactController;
use \App\Http\Controllers\Api\Admin\SettingMainPage\BottomHeaderMenuController;
use \App\Http\Controllers\Api\Admin\SettingMainPage\MainScreenController;
use \App\Http\Controllers\Api\Admin\SettingMainPage\MainShoppingOfferController;
use \App\Http\Controllers\Api\Admin\SettingMainPage\PopularSpecialtyController;
use \App\Http\Controllers\Api\Admin\SettingMainPage\TextBlockController;
use \App\Http\Controllers\Api\Admin\SettingMainPage\StuffController;
use \App\Http\Controllers\Api\Admin\SettingMainPage\TextBottomBlockController;
use \App\Http\Controllers\Api\Admin\SettingMainPage\FooterController;

Route::controller(LogoController::class)->group(function() {
    // загрузить лого
    Route::post('/admin/setting-main-page/upload-logo', 'uploadLogo');
});

// меню в шапке
Route::controller(HeaderMenuController::class)->group(function() {
    Route::post('admin/setting-main-page/header-menu/create-section', 'createSection');
    Route::post('admin/setting-main-page/header-menu/edit-section', 'editSection');
    Route::get('admin/setting-main-page/header-menu/all-section', 'getSections');
    Route::delete('admin/setting-main-page/header-menu/delete-section/{section_id}', 'deleteSection');
});
// контакты в хедере
Route::controller(ContactController::class)->group(function(){
    Route::post('admin/setting-main-page/contacts/mail' , 'editMail');
    Route::post('admin/setting-main-page/contacts/phone/create' , 'createPhone');
    Route::post('admin/setting-main-page/contacts/phone/edit/{phone_id}' , 'editPhone');
    Route::delete('admin/setting-main-page/contacts/phone/delete/{phone_id}' , 'deletePhone');
    Route::get('admin/setting-main-page/contacts/phone/all', 'allPhones');
    Route::get('admin/setting-main-page/contacts/email', 'getEmail');
});

// меню юзера в зависимости от его типа
Route::controller(BottomHeaderMenuController::class)->group(function(){
    Route::post('admin/setting-main-page/user-menu/item-create' , 'createItem');
    Route::post('admin/setting-main-page/user-menu/item-edit' , 'editItem');
    Route::delete('admin/setting-main-page/user-menu/item-delete/{item_id}' , 'deleteItem');
    Route::get('admin/setting-main-page/user-menu/get' , 'getUserMenu');
});


// главный экран
Route::controller(MainScreenController::class)->group(function(){
    Route::post('admin/setting-main-page/main-screen/update' , 'updateText');
    Route::get('admin/setting-main-page/main-screen/get' , 'getBlock');
    Route::post('admin/setting-main-page/main-screen/load-picture' , 'uploadPicture');
});

// утп 
Route::controller(MainShoppingOfferController::class)->group(function(){
    Route::post('/admin/setting-main-page/utp/create' , 'createUtp');
    Route::post('/admin/setting-main-page/utp/edit/{id}' , 'editUtp');
    Route::delete('/admin/setting-main-page/utp/delete/{id}' , 'deleteUtp');
    Route::get('/admin/setting-main-page/utp/get-all' , 'getAllUtp');
});

// популярные специальности
Route::controller(PopularSpecialtyController::class)->group(function(){
    Route::post('/admin/setting-main-page/popular-specialty/sync' , 'syncSpecialties');
    Route::get('/admin/setting-main-page/popular-specialty/get-all' , 'getSpecialties');
});

// текстовые блоки
Route::controller(TextBlockController::class)->group(function(){
    Route::get('/admin/setting-main-page/text-blocks/get', 'getBlocks');
    Route::post('/admin/setting-main-page/text-blocks/update/{block_id}', 'updateBlock');
    Route::post('/admin/setting-main-page/text-blocks/update-img/{block_id}', 'updateImgBlock');
});

// сотрудники
Route::controller(StuffController::class)->group(function(){
    Route::get('/admin/setting-main-page/stuff/get', 'getStuffs');
    Route::post('/admin/setting-main-page/stuff/create', 'createStuff');
    Route::post('/admin/setting-main-page/stuff/update/{id}', 'updateStuff');
    Route::post('/admin/setting-main-page/stuff/update-photo/{id}', 'updatePhotoStuff');
    Route::delete('/admin/setting-main-page/stuff/delete/{id}', 'deleteStuff');
});

// настройка нижних текстовых блоков на главной
Route::controller(TextBottomBlockController::class)->group(function(){
    Route::post('/admin/setting-main-page/text-blocks-bottom/create', 'createBlock');
    Route::post('/admin/setting-main-page/text-blocks-bottom/edit/{id}', 'updateBlock');
    Route::post('/admin/setting-main-page/text-blocks-bottom/edit-picture/{id}', 'updateBlockPicture');
    Route::get('/admin/setting-main-page/text-blocks-bottom/all', 'getBlocks');
    Route::delete('/admin/setting-main-page/text-blocks-bottom/delete/{id}', 'deleteBlock');
});

// Настройки футера
Route::controller(FooterController::class)->group(function(){
    // Разделы
    Route::post('admin/setting-main-page/footer-sections/create', 'createSectionFooter');
    Route::post('admin/setting-main-page/footer-sections/update/{id}', 'updateSectionFooter');
    Route::delete('admin/setting-main-page/footer-sections/delete/{id}', 'deleteSectionFooter');
    Route::get('admin/setting-main-page/footer-sections/all', 'getSectionsFooter');
    // Пункты меню
    Route::post('admin/setting-main-page/footer-sections/items/create/{id}', 'createSectionFooterItem');
    Route::post('admin/setting-main-page/footer-sections/items/update/{id}', 'updateSectionFooterItem');
    Route::delete('admin/setting-main-page/footer-sections/items/delete/{id}', 'deleteSectionFooterItem');
    // логотипы (верхние)
    Route::post('/admin/setting-main-page/footer-top-logo/create', 'createTopLogo');
    Route::post('/admin/setting-main-page/footer-top-logo/update/{id}', 'updateTopLogo');
    Route::post('/admin/setting-main-page/footer-top-logo/load-picture/{id}', 'loadTopLogo');
    Route::delete('/admin/setting-main-page/footer-top-logo/delete/{id}', 'deleteTopLogo');
    Route::get('/admin/setting-main-page/footer-top-logo/all', 'allTopLogo');
    // логотипы(нижние)
    Route::post('/admin/setting-main-page/footer-bottom-logo/create', 'createBottomLogo');
    Route::post('/admin/setting-main-page/footer-bottom-logo/update/{id}', 'updateBottomLogo');
    Route::post('/admin/setting-main-page/footer-bottom-logo/load-picture/{id}', 'loadBottomLogo');
    Route::delete('/admin/setting-main-page/footer-bottom-logo/delete/{id}', 'deleteBottomLogo');
    Route::get('/admin/setting-main-page/footer-bottom-logo/all', 'allBottomLogo');
    // соц. сети
    Route::post('/admin/setting-main-page/footer-socials/create', 'createSocial');
    Route::post('/admin/setting-main-page/footer-socials/update/{id}', 'updateSocial');
    Route::delete('/admin/setting-main-page/footer-socials/delete/{id}', 'deleteSocial');
    Route::get('/admin/setting-main-page/footer-socials/all', 'allSocials');
    // Блоки с ссылками
    Route::post('/admin/setting-main-page/footer-block-refs/create', 'createBlockRef');
    Route::post('/admin/setting-main-page/footer-block-refs/update/{id}', 'updateBlockRef');
    Route::delete('/admin/setting-main-page/footer-block-refs/delete/{id}', 'deleteBlockRef');
    Route::get('/admin/setting-main-page/footer-block-refs/all', 'allBlocksRef');
});







