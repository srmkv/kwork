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
        Schema::create('passport_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('passport_id')->constrained('passports')->nullable();
            $table->unsignedBigInteger('user_id')->constrained()->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('passport_user');
    }
};
