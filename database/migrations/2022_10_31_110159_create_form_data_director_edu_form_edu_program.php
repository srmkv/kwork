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

        // таблица связывает участие в программах и директора ( спец. раздел - формы)
        Schema::create('edu_director_edu_program', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('form_edu_program_id'); // программы в спец. разделе
            $table->unsignedBigInteger('form_data_director_edu_id'); // форма с инфой руководителя

            $table->foreign('form_edu_program_id')->references('id')->on('form_edu_programs');
            $table->foreign('form_data_director_edu_id')->references('id')->on('form_data_director_edus');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edu_director_edu_program');
    }
};
