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
        Schema::create('user_employees', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('employee_role_id')->constrained();
            $table->unsignedBigInteger('individual_profile_id');
            $table->string('phone')->unique();
            $table->unsignedBigInteger('business_profile_id');
            $table->integer('ip_profile_id')->nullable();
            $table->string('status')->default('waiting');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_employees');
    }
};
