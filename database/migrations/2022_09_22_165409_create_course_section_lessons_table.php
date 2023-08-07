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
        Schema::create('course_section_lessons', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(); 
            $table->string('description')->nullable();
            $table->unsignedBigInteger('course_section_id')->nullable();
            $table->unsignedSmallInteger('type_id')->nullable();
            $table->tinyInteger('show_task_answers')->default(0);
            $table->tinyInteger('show_materials')->default(0);
            $table->tinyInteger('show_comments')->default(0);
            $table->tinyInteger('show_teachers')->default(0);
            $table->string('link')->nullable();
            $table->date('date')->nullable();
            $table->string('hours')->nullable();
            $table->unsignedSmallInteger('study_form_id')->nullable();
            $table->string('address')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_section_lessons');
    }
};
