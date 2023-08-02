<?php
use \App\Http\Controllers\Api\Payment\PaymentController;
use \App\Http\Controllers\Api\Payment\PayInstallmentController;

Route::controller(PaymentController::class)->group(function(){
    # БЕЗНАЛ 
    //текущий статус платежа
    Route::get('/payment/state', 'getState');
    //текущий статус заказа
    Route::get('/payment/order', 'getOrder');
    //создать платеж
    Route::post('/payment/create', 'createOrder');
    // все типы платежей
    Route::get('payment/types/all', 'typePaymentsAll');
    // посмотреть платеж в базе
    Route::get('/payment/get/{payment_id}', 'getPaymentById');
    
    // DEV
    // получение карт кастомера (напрямую)
    Route::get('payment/dev-get-customer/{customerKey}', 'devUserCards');
    // удалить кастомера
    Route::delete('payment/delete-customer/{customerKey}', 'removeCustomerMerchant');
    // получение кастомера по ключу
    Route::get('payment/get-customer/{customerKey}', 'getCustomerMerchant');
    // список банковских карт пользователя
    Route::get('/payment/get-cards-list', 'getUserCards');
    // удалить карту
    Route::post('/payment/remove-card', 'removePaymentCard');
    // получение статуса заказа
    Route::get('/payment/get-dev-order', 'getCheckOrder');
    // посмотреть ответ нотификации вручную
    Route::post('payment/check/{id}' , 'checkNotify');
    // создание кастомера, для дальнейших манипуляций
    Route::post('payment/create-customer', 'newCustomerMerchant');
    // переотправить нотификации
    Route::get('payment/notify/resend', 'resendHook');
}); 

# РАССРОЧКИ / КРЕДИТЫ
Route::controller(PayInstallmentController::class)->group(function(){
    Route::get('/payment/installment/list-info', 'getInstallmentsByType');
    Route::post('/payment/installment/create', 'firstPayInstallment');
    // ОТ ТИНЬКОФФ
    Route::get('/payment/installment/tinkoff/types', 'getTinkoffTypes');
    // ОТ КВАЛИФИТЕРЫ
    // новая рассрочка
    Route::post('/payment/installment-qualifiterra/create', 'firstPayInstallmentQualifiterra');
    // вторичная оплата активной рассрочки  
    Route::post('/payment/installment-qualifiterra/resend-pay', 'resendPayInstallmentQualifiterra');
    // получим текущую рассрочку ( последняя из запомненых)
    Route::get('/payment/installment-qualifiterra/get/{order_id}', 'getInstallmentProcessForOrder');
});