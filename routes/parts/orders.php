<?php
use App\Http\Controllers\Api\Course\Order\OrderController;
use App\Http\Controllers\Api\Course\Order\BusinessOrderController;
use App\Http\Controllers\Api\Course\Order\SubmissionDocumentsController;

Route::controller(OrderController::class)->group(function () {
    //проверка документов на странице подачи заявки
    Route::post('/order/check-documents', 'checkOrder');
    // проверка документов и вывод подходящих документов в заявку
    Route::get('/order/match-documents/{course_id}', 'matchOrder');
    // когда юзер пытается выбрать определенные документы, проверка в заявке
    Route::post('/order/user-cheked/{course_id}', 'userCheckedDocuments');
    //создание заявки
    Route::post('/order/create' , 'newOrder');
    Route::get('/order/{order_id}' , 'getOrderById');
    Route::get('/user/orders' , 'getOrders');
    //все существующие статусы
    Route::get('order-statuses', 'getStatusesOrder');
    // документооборот
    Route::get('order/document-flow', 'documentFlow');
    // программа курса/заявки
    Route::post('order/programm', 'programmOrder');
    //поиск ордеров по названию курса
    Route::get('/search/order', 'searchOrders');
});


Route::controller(SubmissionDocumentsController::class)->group(function(){
    // документы на зачисление юзера внутри заявки
    Route::get('order/admission-documents/get/{order_id}' , 'getAdmissionDocuments');
    // когда в какой то конкретный документ загружаем новый 
    // или редактируем существующий комплект документов
    Route::post('order/admission-documents/edit/{order_id}' , 'editAdmissionDocuments');
    // адмишен по ид
    Route::get('order/get-admission/{admission_id}' , 'getAdmissionById');
});

Route::controller(BusinessOrderController::class)->group(function () {
    // создание заявки юр. лицом
    Route::post('/order/business/create' , 'newBusinessOrder');
    // изменение бизнес заявки
    Route::post('/order/business/edit/{order_id}' , 'editBusinessOrder');
    // filter
    Route::post('/order/business/filter' , 'filterBusinessOrder');
    // price check rules
    Route::get('/order/business/price' , 'getPriceBusinessOrder');
    // бизнес заявка внутри
    Route::get('/order/business/get/{order_id}' , 'getBusinessOrder');

    // TAB COURSE
    // курсы внутри заявки
    Route::get('/order/business/get-courses/{order_id}' , 'getCoursesForOrder');
    //получить слушателей внутри одного потока (курса), внутри конкретной заявки
    Route::get('/order/business/get-listeners-course' , 'getListenersIntoCourse');
    // добавить слушателя к курсу
    Route::post('/order/business/create-listeners-course', 'createStudentsCourse');
    // удалить студента с курса
    Route::delete('/order/business/delete-listeners-course', 'deleteStudentCourse');
    // удалить курс из заявки вместе со студентами и их сущностями
    Route::delete('/order/business/delete-course', 'deleteCourse');
    // добавить курс в существующую заявку ( кнопка добавить курс)
    Route::post('/order/business/create-course', 'createCourse');
    // FILTERS
    // фильтруем курсы в заявке
    Route::post('/order/business/filter-courses', 'resultFilters');
    // инфа для курсов
    Route::get('/order/business/filter-info/{business_order_id}', 'infoFilters');

    // TAB LISTENERS(STUDENTS)
    // список всех слушателей внутри заявки юр.лица
    Route::get('/order/business/listeners-all/{order_id}', 'listenersInformation');
    // удалить студента целиком из бизнес заявки ( с курсами, ордерами и т.д)
    Route::delete('order/business/listeners-delete', 'deleteStudentEntireBusiness');

    // OTHER
    // список всех заявок конкретного юр.лица
    Route::get('/order/business/list', 'listOrders');
    // прикрепить несколько заявок при оплате одного пакета
    Route::post('/order/business/multi-orders/packet-pay', 'multiOrders');

});


