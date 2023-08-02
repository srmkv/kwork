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
        // таблица связывает один(!) обязательный документ об образовании с его
        // заменяющими документами из высшего образования 
        Schema::create('required_replace_higher_edu', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_edu_id')->nullable();
            $table->unsignedBigInteger('higher_edu_speciality_id')->constrained();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('required_replace_higher_edu');
    }
};
