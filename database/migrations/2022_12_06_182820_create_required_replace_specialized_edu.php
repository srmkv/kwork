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
        Schema::create('required_replace_specialized_edu', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_edu_id')->nullable();
            $table->unsignedBigInteger('specialized_secondary_speciality_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('required_replace_specialized_edu');
    }
};
