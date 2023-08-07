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
        Schema::create('section_year_documents', function (Blueprint $table) {
            $table->id();
            // $table->timestamps();
            $table->string('section_title')->nullable();
            $table->unsignedBigInteger('activity_plan_year_id')->constrained();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('section_year_documents');
    }
};
