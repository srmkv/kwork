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

        Schema::dropIfExists('passports');
        Schema::create('passports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id')->constrained()->nullable();
            $table->unsignedBigInteger('user_id')->constrained()->nullable();
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('issued_by_whom')->nullable();
            $table->date('date_issue')->nullable();
            $table->string('subdivision_code')->nullable();
            $table->string('citizenship')->default('RF');
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
        Schema::dropIfExists('passports');
    }
};
