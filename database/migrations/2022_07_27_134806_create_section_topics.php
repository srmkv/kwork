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
        Schema::create('section_topics', function (Blueprint $table) {
            $table->id();
            // $table->timestamps();
            $table->string('title')->nullable();
            $table->integer('duration_hours')->default(1);
            $table->time('event_time', $precision = 2)->nullable();
            $table->unsignedBigInteger('course_section_id')->constrained(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('section_topics');
    }
};
