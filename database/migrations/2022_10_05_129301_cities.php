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
        Schema::dropIfExists('cities');
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->integer('city_id')->default(0);

            $table->unsignedBigInteger('country_id')->constrained();
            $table->unsignedBigInteger('region_id')->constrained();

            $table->string('title_ru')->nullable();
            $table->string('title_en')->nullable();

            
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
};
