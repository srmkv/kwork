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
        Schema::create('course_higher_speciality', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id')->constrained();
            $table->unsignedBigInteger('higher_edu_speciality_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_higher_speciality');
    }
};
