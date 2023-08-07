<?php
use \App\Http\Controllers\Api\Admin\InfoEduOrganization;

// СВЕДЕНИЯ ОБ ОБР. ОРГАНИЗАЦИИ (ADMIN SPECIAL SECTION)
Route::controller(InfoEduOrganization::class)->group(function(){




    // ** SECTIONS **
    // создать раздел
    Route::post('/admin/admin-section/create', 'createSection');
    // изменить раздел
    Route::post('/admin/admin-section/edit', 'editSection');
    // удалить раздел
    Route::delete('/admin/admin-section/delete', 'deleteSection');
    // сортировка разделов
    Route::post('/admin/admin-section/sort', 'positionSections');

    // ** SECTION TAB ** Вкладка которая может быть, а может не быть в разделе
    Route::post('/admin/admin-section-tab/create', 'createSectionTab');   
    Route::post('/admin/admin-section-tab/edit', 'editSectionTab');   
    Route::delete('/admin/admin-section-tab/delete/{tab_id}', 'deleteSectionTab');   
    

    // ** SPOILERS **
    // прикрепить спойлер к разделу
    Route::post('/admin/admin-section/spoiler/create', 'createSpoiler');
    // изменить спойлер
    Route::post('/admin/admin-section/spoiler/edit', 'editSpoiler');
    // удалить спойлер
    Route::delete('/admin/admin-section/spoiler/delete/{spoiler_id}', 'deleteSpoiler');
    //получить спойлеры с позициями
    Route::get('/admin/admin-section/spoiler/all', 'getSpoilers');
    //позиции/сортировка спойлеров
    Route::post('/admin/admin-section/spoiler/position', 'positionSpoilers');
    
    // ** FORMS (DATA ORG.) **
    // прикрепить пустую форму к спойлеру
    Route::post('/admin/admin-section/spoiler/forms/data-org/create', 'createForm');
    //удалить data org 
    Route::delete('/admin/admin-section/spoiler/forms/data-org/delete/{form_id}', 'deleteForm');
    // правим форму данные об обр. орг
    Route::post('/admin/admin-section/spoiler/forms/data-org/edit', 'editForm');
    // добавить пустой email к форме
    Route::post('/admin/admin-section/spoiler/forms/data-org/email/create', 'createEmail');
    // удалить емейл
    Route::delete('/admin/admin-section/spoiler/forms/data-org/email/delete/{email_id}', 'deleteEmail');
    // поменять емейл
    Route::post('/admin/admin-section/spoiler/forms/data-org/email/edit', 'editEmail');
    // добавить пустой phone
    Route::post('/admin/admin-section/spoiler/forms/data-org/phone/create', 'createPhone');
    // поменять phone
    Route::post('/admin/admin-section/spoiler/forms/data-org/phone/edit', 'editPhone');
    //удалить телефон
    Route::delete('/admin/admin-section/spoiler/forms/data-org/phone/delete/{phone_id}', 'deletePhone');

    // ** FORMS VACANT PLACES
    // пустая форма с вакантными местами
    Route::post('/admin/admin-section/spoiler/forms/vacant-place/create', 'createVacantPlacesForm');
    Route::post('/admin/admin-section/spoiler/forms/vacant-place/edit', 'editVacantPlacesForm');
    Route::delete('/admin/admin-section/spoiler/forms/vacant-place/delete/{form_id}', 'deleteVacantPlacesForm');

    // ** FORMS (UNIT ORG.) **
    //пустое структурное подразделение
    Route::post('/admin/admin-section/spoiler/forms/unit-org/create', 'createUnitForm');
    // заполнить данными
    Route::post('/admin/admin-section/spoiler/forms/unit-org/edit', 'editUnitForm');
    // получить список документов
    Route::get('/admin/admin-section/spoiler/forms/unit-org/{form_id}/docs', 'docsUnitForm');
    //пустой емейл для подразделения
    Route::post('/admin/admin-section/spoiler/forms/unit-org/email/create', 'createEmailUnitForm');
    // поменять емейл подразделения
    Route::post('/admin/admin-section/spoiler/forms/unit-org/email/edit', 'editEmailUnitForm');
    // удалить емейл подразделения
    Route::delete('/admin/admin-section/spoiler/forms/unit-org/email/delete/{email_id}', 'deleteEmailUnitForm');
    //пустой сайт для подразделения
    Route::post('/admin/admin-section/spoiler/forms/unit-org/site', 'createSiteUnitForm');
    // поменять сайт подразделения
    Route::post('/admin/admin-section/spoiler/forms/unit-org/site/edit', 'editSiteUnitForm');
    // удалить сайт подразделения
    Route::delete('/admin/admin-section/spoiler/forms/unit-org/site/delete/{site_id}', 'deleteSiteUnitForm');
    // вся инфа подразделения
    Route::get('/admin/admin-section/spoiler/forms/unit-org/{form_id}', 'getUnitForm');
    //удалим подразделение вместе со связями
    Route::delete('/admin/admin-section/spoiler/forms/unit-org/delete/{form_id}', 'deleteUnitForm');
    //удалить документ который прикрепляли
    Route::delete('/admin/admin-section/spoiler/forms/unit-org/delete-doc/{doc_id}', 'deleteUnitFormDocument');


    // ** FORMS (EDU PROGRAMM) **
    // пустая программа
    Route::post('/admin/admin-section/spoiler/forms/edu-prog/create', 'createEduProgrammForm');
    // меняем программу (Получается эти программы не актуальны, т.к берем их в курсе)
    Route::post('/admin/admin-section/spoiler/forms/edu-prog/edit', 'editEduProgrammForm');
    Route::get('/admin/admin-section/spoiler/forms/edu-prog-all', 'getEduAllProgrammForm');
    // удалить программу
    Route::delete('/admin/admin-section/spoiler/forms/edu-prog/delete/{form_id}', 'deleteEduProgrammForm');
    // выведем список из названий курсов
    Route::get('/admin/admin-section/spoiler/forms/course-list', 'getCourseListEduProgrammForm');
    //удалим документ pdf из формы edu programm
    Route::delete('/admin/admin-section/spoiler/forms/edu-prog/doc-delete/{doc_id}', 'deleteDocEduProgramForm');

    




    // ** FORMS (MATERIAL EQUIPMENT / DESCRIPTION) **
    // пустое мат. оснащение
    Route::post('/admin/admin-section/spoiler/forms/mat-equip/create', 'createMatEquipForm');
    // изменить мат.оснащение
    Route::post('/admin/admin-section/spoiler/forms/mat-equip/edit', 'editMatEquipForm');
    // удалить мат.оснащение
    Route::delete('/admin/admin-section/spoiler/forms/mat-equip/delete/{form_id}', 'deleteMatEquipForm');
    // получить мат. оснащение
    Route::get('/admin/admin-section/spoiler/forms/mat-equip/{form_id}', 'getMatEquipForm');

    // **FORMS FELLOWSHIPS AND SUPPORT MEASURES
    //добавить стипендию
    Route::post('/admin/admin-section/spoiler/forms/fellowship/create', 'createFellowMeasureForm');
    //изменить стипендию
    Route::post('/admin/admin-section/spoiler/forms/fellowship/edit', 'editFellowMeasureForm');
    //удалить стипендию
    Route::delete('/admin/admin-section/spoiler/forms/fellowship/delete/{form_id}', 'deleteFellowMeasureForm');
    //инфо формы стипендии
    Route::get('/admin/admin-section/spoiler/forms/fellowship/{form_id}', 'getFellowMeasureForm');

    // **FORMS DATA DIRECTOR(1)
    // добавить форму руководителя
    Route::post('/admin/admin-section/spoiler/forms/data-director/create', 'createDataDirectorForm');
    // изменить данные руководителя
    Route::post('/admin/admin-section/spoiler/forms/data-director/edit', 'editDataDirectorForm');
    //получить руководителя
    Route::get('/admin/admin-section/spoiler/forms/data-director/{form_id}', 'getDataDirectorForm');
    //удалить форму руководителя
    Route::delete('/admin/admin-section/spoiler/forms/data-director/delete/{form_id}', 'deleteDataDirectorForm');

    // **FORMS EDU DIRECTOR(2)
    // добавить форму руководителя
    Route::post('/admin/admin-section/spoiler/forms/edu-director/create', 'createEduDirectorForm');
    // изменить данные руководителя
    Route::post('/admin/admin-section/spoiler/forms/edu-director/edit', 'editEduDirectorForm');
    //получить руководителя
    Route::get('/admin/admin-section/spoiler/forms/edu-director/{form_id}', 'getEduDirectorForm');
    //удалить форму руководителя
    Route::delete('/admin/admin-section/spoiler/forms/edu-director/delete/{form_id}', 'deleteEduDirectorForm');
    // получить программы в которых участвовал руководитель
    Route::get('/admin/admin-section/spoiler/forms/edu-director/prog/{form_id}', 'getProgrammsEduDirectorForm');
    //синхронизировать чекбоксы программ
    Route::post('/admin/admin-section/spoiler/forms/edu-director/prog/sync', 'attachProgrammsEduDirectorForm');

    // **FORMS DOC AS (PIC/REF)
    //добавить форму документа
    Route::post('/admin/admin-section/spoiler/forms/docs/create', 'crateDocForm');
    // изменить форму документа
    Route::post('/admin/admin-section/spoiler/forms/docs/edit', 'editDocForm');
    // получить форму
    Route::get('/admin/admin-section/spoiler/forms/docs/{form_id}', 'getDocForm');
    // удалить форму вместе с документами
    Route::delete('/admin/admin-section/spoiler/forms/docs/delete/{form_id}', 'deleteDocForm');
    //удалить pdf файл
    Route::delete('/admin/admin-section/spoiler/forms/docs/delete-pdf/{doc_id}', 'deletePdfDocForm');

    //**FORMS ACCESIBLE  ENVIRONMENT
    //пустая доступная среда
    Route::post('/admin/admin-section/spoiler/forms/env/create', 'createEnvForm');
    //изменить среду
    Route::post('/admin/admin-section/spoiler/forms/env/edit', 'editEnvForm');
    // вывести по id дост. среду
    Route::get('/admin/admin-section/spoiler/forms/env/{form_id}', 'getEnvForm');
    //удалить доступную среду
    Route::delete('/admin/admin-section/spoiler/forms/env/delete/{form_id}', 'deleteEnvForm');
    //изменить описание для конкретной картинки
    Route::post('/admin/admin-section/spoiler/forms/env/image/edit', 'editImageDescEnvForm');
    //удалить картинку из формы
    Route::delete('/admin/admin-section/spoiler/forms/env/image/delete/{image_id}', 'deleteImageEnvForm');

    //**FORMS EDUCATION
    //пустое обучение
    Route::post('/admin/admin-section/spoiler/forms/edu/create', 'crateEduForm');
    //изменить среду
    Route::post('/admin/admin-section/spoiler/forms/edu/edit', 'editEduForm');
    // вывести по id дост. среду
    Route::get('/admin/admin-section/spoiler/forms/edu/{form_id}', 'getEduForm');
    //удалить доступную среду
    Route::delete('/admin/admin-section/spoiler/forms/edu/delete/{form_id}', 'deleteEduForm');

    //**FORMS INTERNATIONAL COOPERATION
    //пустое сотрудничество
    Route::post('/admin/admin-section/spoiler/forms/coop/create', 'crateCoopForm');
    //изменить межд. сотруд
    Route::post('/admin/admin-section/spoiler/forms/coop/edit', 'editCoopForm');
    // вывести по id межд сотруднич.
    Route::get('/admin/admin-section/spoiler/forms/coop/{form_id}', 'getCoopForm');
    //удалить сотрудничество
    Route::delete('/admin/admin-section/spoiler/forms/coop/delete/{form_id}', 'deleteCoopForm');
    //удалить фотку в сотрудничестве
    Route::delete('/admin/admin-section/spoiler/forms/coop/delete-image/{image_id}', 'deleteImageCoopForm');

    //**FORMS FINANCIAL SOURCE
    //пустое финансирование
    Route::post('/admin/admin-section/spoiler/forms/fsource/create', 'crateFinancialSourceForm');
    //изменить финансирование
    Route::post('/admin/admin-section/spoiler/forms/fsource/edit', 'editFinancialSourceForm');
    // вывести финансирование с годами
    Route::get('/admin/admin-section/spoiler/forms/fsource/{form_id}', 'getFinancialSourceForm');
    //удалить финансирование
    Route::delete('/admin/admin-section/spoiler/forms/fsource/delete/{form_id}', 'deleteFinancialSourceForm');
    
    //YEARS
    //прикрепить год к форме
    Route::post('/admin/admin-section/spoiler/forms/fsource/year/create', 'crateYearFinancialSourceForm');
    //меняем год
    Route::post('/admin/admin-section/spoiler/forms/fsource/year/edit', 'editYearFinancialSourceForm');
    //удалить год
    Route::delete('/admin/admin-section/spoiler/forms/fsource/year/delete/{fsource_id}', 'deleteYearFinancialSourceForm');

    //**FORMS FINANCIAL ECONOMIC ACTIVITY PLAN
    //пустой план финансов хозяйственной деятельности
    Route::post('/admin/admin-section/spoiler/forms/plan/create', 'crateActivityPlanForm');
    //изменить план
    Route::post('/admin/admin-section/spoiler/forms/plan/edit', 'editActivityPlanForm');
    //удалить план
    Route::delete('/admin/admin-section/spoiler/forms/plan/delete/{form_id}', 'deleteActivityPlanForm');
    //получить план хоз. деят. с зависимостями
    Route::get('/admin/admin-section/spoiler/forms/plan/{plan_id}', 'getActivityPlanForm');
    //PLAN YEAR
    //прикрепить форму с годом
    Route::post('/admin/admin-section/spoiler/forms/plan/year/create', 'createPlanYearForm');
    //изменить форму с годом
    Route::post('/admin/admin-section/spoiler/forms/plan/year/edit', 'editPlanYearForm');
    //удалить форму с годом
    Route::delete('/admin/admin-section/spoiler/forms/plan/year/delete/{year_id}', 'deletePlanYearForm');
    //получить форму года с документами
    Route::get('/admin/admin-section/spoiler/forms/plan/year/{year_id}', 'getPlanYearForm');
    //SECTION DOCUMENTS (INTO YEAR)
    //пустая секция
    Route::post('/admin/admin-section/spoiler/forms/plan/year/section/create', 'createSectionYearDocumentForm');
    // //поменять секцию(название)
    Route::post('/admin/admin-section/spoiler/forms/plan/year/section/edit', 'editSectionYearDocumentForm');
    // удалить секцию вместе с документами
    Route::delete('/admin/admin-section/spoiler/forms/plan/year/section/delete/{section_id}', 'deleteSectionYearDocumentForm');
    //удалить документ отдельно
    Route::delete('/admin/admin-section/spoiler/forms/plan/year/section/delete-doc/{doc_id}', 'deleteDocInSectionYear');
    //изменить год у pdf файла
    Route::post('/admin/admin-section/spoiler/forms/plan/year/section/edit-doc', 'editDocInSectionYear');

    //**FORMS SPECIALITY
    //пустая специальность
    Route::post('/admin/admin-section/spoiler/forms/speciality/create', 'createSpecialityForm');
    //изменить специальность
    Route::post('/admin/admin-section/spoiler/forms/speciality/edit', 'editSpecialityForm');
    //удалить специальность
    Route::delete('/admin/admin-section/spoiler/forms/speciality/delete/{speciality_id}', 'deleteSpecialityForm');
    //получить спец.
    Route::get('/admin/admin-section/spoiler/forms/speciality/{speciality_id}', 'getSpecialityForm');

});