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
        Schema::create('form_education', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('course_id')->nullable()->constrained();
            $table->unsignedBigInteger('admin_section_spoiler_id')->constrained();
            // $table->string('title_speciality')->nullable(); //наименование специальности
            // $table->string('direction_training')->nullable(); //направление подготовки
            // $table->string('title_programm')->nullable(); //название программы
            // $table->string('level_education')->nullable(); // уровень образования
            // $table->string('implemented_forms_education')->nullable(); // реализуемые формы обучения
            // $table->string('description_programm')->nullable(); // описание образовательной программы
            // $table->string('academic_plan')->nullable(); // учебный план
            // $table->string('calendar_training_schedule')->nullable(); // календарный учебный график
            // $table->string('description')->nullable(); 
            // $table->string('teachers')->nullable(); 

            $table->string('form_type')->default('form_education');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_education');
    }
};
