<?php
use \App\Http\Controllers\Callback\smsRu;
use \App\Http\Controllers\Api\Payment\PaymentController;
use \App\Http\Controllers\Api\Payment\PayInstallmentController;

//CALBACK
Route::post('/callback/smsru', [smsRu::class, 'postSmsRu']);
Route::post('/payment/notify', [PaymentController::class, 'notifyHook'] );
Route::post('/payment/notify-qualifiterra-installment', [PayInstallmentController::class, 'notifyHook'] );
