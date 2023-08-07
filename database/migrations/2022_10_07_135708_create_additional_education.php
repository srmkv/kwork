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
        Schema::create('additional_education', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('country_id')->contstrained('countries')->default(1);
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('user_id')->contstrained();
            $table->string('title_additional')->nullable();
            $table->string('type_edu')->nullable();
            $table->string('edu_organization')->nullable();
            $table->string('specialty_qualification')->nullable();
            $table->date('year_start')->nullable();
            $table->date('year_ended')->nullable();
            $table->unsignedBigInteger('speciality_id')->nullable()->contstrained();
            $table->string('additional_serial_number')->nullable();
            $table->timestamps();
            


            //custom 11.17.22
            // $table->string('title_additional_id')->nullable();

            // $table->unsignedBigInteger('type_edu_id')->contstrained();
            // $table->unsignedBigInteger('edu_organization_id')->contstrained();


            $table->integer('hours')->default(0);
            $table->unsignedBigInteger('direction_id')->nullable()->contstrained();

            
            //custom 16.11.22

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('additional_education');
    }
};
