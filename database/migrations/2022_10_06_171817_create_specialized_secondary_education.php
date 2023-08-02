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
        Schema::create('specialized_secondary_education', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id')->contstrained('countries')->default(1);
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('user_id')->contstrained();
            $table->string('title_specialized_school')->nullable();
            $table->string('title_specialized_school_id')->nullable();
            $table->year('year_start')->nullable();
            $table->year('year_ended')->nullable();
            $table->date('date_of_issue')->nullable();
            $table->string('group_school')->nullable();
            $table->string('speciality_id')->contstrained()->nullable();
            $table->string('specialized_school_serial_number')->contstrained()->nullable();
            $table->unsignedSmallInteger('direction_id')->nullable();
            $table->json('status_doc')->nullable();
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
        Schema::dropIfExists('specialized_secondary_education');
    }
};
