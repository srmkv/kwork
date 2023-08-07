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
        Schema::create('form_economic_activity_plans', function (Blueprint $table) {
            $table->id();
            $table->timestamps();  
            $table->unsignedBigInteger('admin_section_spoiler_id')->constrained();
            $table->string('title_form')->nullable();
            $table->string('description')->nullable();
            $table->integer('display_as')->default(1);

            //custom
            $table->string('form_type')->default('form_economic_activity_plans');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_economic_activity_plans');
    }
};
