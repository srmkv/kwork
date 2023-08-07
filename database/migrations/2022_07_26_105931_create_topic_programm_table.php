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
        Schema::create('topic_programm', function (Blueprint $table) {
            $table->id();
            // $table->timestamps();

            $table->unsignedBigInteger('topic_list_programm_id')->constrained();
            $table->unsignedBigInteger('course_programm_id')->constained();




        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topic_programm');
    }
};
