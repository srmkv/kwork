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
        Schema::create('profiles_buiseness', function (Blueprint $table) {
            $table->id();
            // COMMON INFO
            $table->unsignedBigInteger('country_id')->contstrained();
            $table->string('activity_type')->default('DELO #1');
            $table->unsignedBigInteger('tax_type_id')->contstrained();
            $table->string('inn')->nullable();
            $table->string('kpp')->nullable();
            $table->string('ogrn')->nullable();
            $table->string('bik')->nullable();
            $table->string('correspondent_account')->nullable(); 
            $table->string('fact_address')->nullable(); 
            $table->string('bank_account')->nullable();
            $table->string('title_bank')->nullable(); 
            $table->string('full_title')->nullable();
            $table->string('short_title')->nullable();
            $table->string('buiseness_address')->nullable();
            $table->string('management_position')->nullable();
            $table->timestamps();
            $table->string('act_basis')->nullable();
            $table->string('phone_company')->nullable();
            $table->string('mail_company')->nullable();
            $table->unsignedBigInteger('user_id')->contstrained();
            $table->string('mailing_address')->nullable();
            $table->string('index')->nullable();
            $table->integer('media_act_id')->nullable();
            $table->integer('media_logo_id')->nullable();
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles_buiseness');
    }
};
