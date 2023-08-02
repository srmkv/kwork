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
        Schema::create('category_course_speciality_faqs', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->text('answer')->nullable();
            $table->integer('position')->nullable();
            $table->unsignedInteger('course_speciality_id')->nullable();
            $table->unsignedSmallInteger('contains_subsections')->default(0)->nullable();
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
        Schema::dropIfExists('category_course_speciality_faqs');
    }
};
