<?php
use \App\Http\Controllers\Api\User\DashboardController;
use \App\Http\Controllers\Api\User\AddressController;

Route::controller(DashboardController::class)->group(function(){
    Route::get('user/dashboard', 'selectProfile');
    
});

Route::controller(AddressController::class)->group(function(){
    Route::post('user/address', 'newAddress');
    Route::post('user/address/edit/{address_id}', 'editAddress');
    Route::get('user/address/all', 'allAddress');
    Route::get('user/address/choosen', 'getAddressChoosen');
    Route::delete('user/address/delete/{address_id}', 'deleteAddress');
});
