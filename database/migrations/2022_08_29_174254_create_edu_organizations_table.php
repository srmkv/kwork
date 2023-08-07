<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Образовательная организация
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_organizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->string('legal_address')->nullable();
            $table->string('actual_address')->nullable();
            $table->unsignedBigInteger('INN')->nullable();
            $table->unsignedBigInteger('KPP')->nullable();
            $table->unsignedBigInteger('OGRN')->nullable();
            $table->unsignedBigInteger('BIK')->nullable();
            $table->string('corr_account')->nullable();
            $table->string('сhecking_account')->nullable();
            $table->string('bank')->nullable();
            $table->string('general_director')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable()->unique();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('edu_organizations');
    }
};
