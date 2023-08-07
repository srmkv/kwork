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
        Schema::create('org_unit_docs', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('media_id')->constrained(); // сам документ тут
            $table->string('title_doc')->nullable();
            $table->unsignedBigInteger('form_org_unit_id')->constrained(); // форма в которой этот документ находится
            $table->string('preview_pdf')->nullable();
            $table->string('url_pdf')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('org_unit_docs');
    }
};
