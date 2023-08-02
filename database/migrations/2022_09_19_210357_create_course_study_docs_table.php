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
        Schema::create('course_study_docs', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('link')->nullable();
            $table->string('description')->nullable();
            $table->unsignedSmallInteger('course_id')->nullable();
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
        Schema::dropIfExists('course_study_docs');
    }
};
