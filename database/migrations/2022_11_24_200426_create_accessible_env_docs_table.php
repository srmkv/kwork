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
        Schema::create('accessible_env_docs', function (Blueprint $table) {
            $table->id();
            // $table->timestamps();

            $table->string('title_doc')->nullable();
            $table->unsignedBigInteger('form_accesible_env_id')->constrained();
            $table->string('image_description')->nullable();
            $table->string('image_url')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accessible_env_docs');
    }
};
