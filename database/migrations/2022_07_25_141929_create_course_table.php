<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('courses');
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            // tab "Редактир. курса"
            $table->unsignedInteger('admin_id')->comment('id юзера который создаёт курс')->nullable();
            $table->unsignedSmallInteger('state_id')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->json('tree')->nullable();
            $table->float('rating')->nullable();

            $table->string('video_type')->nullable(); 
            $table->string('video_file')->nullable();
            $table->string('video_link')->nullable();

            $table->integer('min_price')->nullable();
            $table->integer('max_price')->nullable();

            $table->date('date_min')->nullable();
            $table->date('date_max')->nullable();

            $table->integer('academic_hours')->nullable();
            $table->integer('academic_days')->nullable();

            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->text('faq_description')->nullable();

            // Флаги о доках
            $table->boolean('is_edu_doc_required')->default(0)->comment('Флаг "Обязательный комплект документов для зачисления на обучение"');
            $table->boolean('is_by_doc_req')->default(0)->comment('Флаг "Курс можнупить только при переадаче всего комплекта документов"');
            $table->boolean('is_change_surname')->default(0)->comment('Флаг "Требуется подтверждение о смене ФИО"');

            // Инфа о выдаваемом документе по окончанию курса
            $table->boolean('is_doc_take')->default(0)->comment('Флаг "Документ выдается"');
            
            $table->string('doc_take_title')->nullable()->comment('Название выдаваемого документа');
            $table->string('doc_take_sub_title')->nullable()->comment('Подзаголовок выдаваемого документа');
            $table->string('doc_take_description')->nullable()->comment('Описание выдаваемого документа');
            $table->string('doc_take_image')->nullable()->comment('Фото выдаваемого документа');



            // tab О пакетах
            $table->unsignedSmallInteger('days_before_start')->nullable();
            
            
            $table->string('title_programm')->nullable();
            $table->unsignedSmallInteger('level_education_id')->nullable();
            $table->text('description_study_programm')->nullable();


            //Баннер
            $table->unsignedSmallInteger('banner_type_id')->nullable()->comment('Тип баннера (анимированный/не анимированный)');
            $table->string('banner_color')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('editor_image')->nullable();

            $table->string('preview')->nullable();


            // Разное
            $table->boolean('is_published')->default(0);
            $table->dateTime('date_published')->nullable();
            $table->boolean('edu_organization_id')->nullable();
            $table->boolean('is_restrict_block')->default(0);
            $table->integer('price')->nullable();
            $table->string('address')->nullable();
            $table->smallInteger('count_places_non_residents')->nullable(); 

            $table->integer('age_limit')->default(0);


            $table->boolean('like')->default(0);
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course');
    }
};
