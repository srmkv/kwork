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
        Schema::create('specialized_secondary_specialities', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->unsignedSmallInteger('direction_id')->nullable();
            $table->boolean('moderated')->default(1);
            $table->string('speciality_code')->nullable();
            $table->text('qualification');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('specialized_secondary_specialities');
    }
};
