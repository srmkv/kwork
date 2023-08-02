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
        Schema::create('international_cooperation_images', function (Blueprint $table) {
            $table->id();
            $table->string('title_doc')->nullable();
            $table->unsignedBigInteger('form_international_cooperation_id')->constrained();
            $table->string('preview_pdf')->nullable();
            $table->string('url_pdf')->nullable();
            $table->text('image_url')->nullable();
            $table->integer('media_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('international_cooperation_images');
    }
};
