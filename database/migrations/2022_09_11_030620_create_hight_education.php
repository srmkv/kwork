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
        Schema::create('higher_education', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('level_education_higher_id')->contstrained();
            $table->unsignedBigInteger('country_id')->contstrained('countries');
            $table->unsignedBigInteger('city_id')->contstrained('cities')->nullable();
            $table->unsignedBigInteger('user_id')->contstrained();
            $table->string('educational_title')->nullable();
            $table->string('educational_title_id')->nullable();
            $table->string('faculty')->nullable();
            $table->string('faculty_id')->nullable();
            $table->string('speciality')->nullable();
            $table->string('speciality_id')->nullable();
            $table->string('direction_id')->nullable();
            $table->unsignedBigInteger('study_form_id')->contstrained();
            $table->boolean('complited');
            $table->year('year_ended')->nullable();
            $table->string('serial_number')->nullable();
            $table->integer('region_id')->nullable();
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
        Schema::dropIfExists('higher_education');
    }
};
