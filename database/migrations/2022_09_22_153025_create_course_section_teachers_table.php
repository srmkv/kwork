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
        Schema::create('course_section_teachers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('teacher_id')->constrained()->nullable();
            $table->unsignedInteger('course_lesson_id')->constrained()->nullable();
            $table->unsignedInteger('state_id')->constrained()->nullable();
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
        Schema::dropIfExists('course_section_teachers');
    }
};
