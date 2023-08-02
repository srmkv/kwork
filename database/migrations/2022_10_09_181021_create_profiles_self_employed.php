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
        Schema::create('profiles_self_employed', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->contstrained();
            $table->unsignedBigInteger('country_id')->contstrained();
            $table->string('activity_type')->nullable();
            $table->string('inn')->nullable();
            $table->string('ogrnip')->nullable();
            $table->string('full_title')->nullable();
            $table->date('date_registration')->nullable();
            $table->string('bik')->nullable();
            $table->string('title_bank')->nullable(); 
            // расчетный счёт
            $table->string('bank_account')->nullable();
            $table->string('correspondent_account_')->nullable(); 
            $table->unsignedBigInteger('tax_type_id')->contstrained();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            //custom 14.12.22
            $table->string('type_profile')->default('individual_businessman');
            $table->boolean('status')->default(0);
            $table->timestamps();

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
        Schema::dropIfExists('profiles_self_employed');
    }
};
