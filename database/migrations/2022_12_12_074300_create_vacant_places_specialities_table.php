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
        Schema::create('vacant_places_specialities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id')->constrained();
            $table->integer('budget_allocations_federal_budget')->default(0);
            $table->integer('budget_allocations_subject_rf_budget')->default(0);
            $table->integer('budget_allocations_local_budget')->default(0);
            $table->integer('budget_allocations_individ_business_budget')->default(0);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacant_places_specialities');
    }
};
