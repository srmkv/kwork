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
        Schema::create('need_other_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id')->constrained();
            $table->json('required_types')->nullable();
            $table->string('description')->nullable();
            $table->string('title')->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('need_other_types');
    }
};