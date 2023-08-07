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
        Schema::create('activity_plan_years', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('year')->nullable();
            $table->unsignedBigInteger('form_economic_activity_plan_id')->constrained();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_plan_years');
    }
};
