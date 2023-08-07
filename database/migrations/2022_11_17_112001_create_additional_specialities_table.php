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
        Schema::create('additional_specialities', function (Blueprint $table) {
            $table->id();
            // $table->timestamps();

            $table->string('title')->nullable();
            $table->boolean('moderated')->default(0);

            //custom
            $table->unsignedBigInteger('direction_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('additional_specialities');
    }
};
