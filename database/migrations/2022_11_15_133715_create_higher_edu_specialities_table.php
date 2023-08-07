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
        Schema::create('higher_edu_specialities', function (Blueprint $table) {
            $table->id();
            $table->string('speciality_code')->nullable();
            $table->string('title')->nullable();

            $table->string('direction_id')->nullable();
            $table->string('moderated')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('higher_edu_specialities');
    }
};
