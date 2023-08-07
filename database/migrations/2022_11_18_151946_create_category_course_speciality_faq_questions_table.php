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
        Schema::create('category_course_speciality_faq_questions', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->integer('position')->nullable();
            $table->unsignedSmallInteger('category_course_speciality_faq_id')->nullable();
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
        Schema::dropIfExists('category_course_speciality_faq_questions');
    }
};
