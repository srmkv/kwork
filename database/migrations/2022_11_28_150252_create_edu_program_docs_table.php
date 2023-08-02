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
        Schema::create('edu_program_docs', function (Blueprint $table) {
            $table->id();
            // $table->timestamps();
            $table->string('title_doc')->nullable();
            $table->unsignedBigInteger('form_edu_program_id')->constrained();

            $table->unsignedBigInteger('media_id')->nullable()->constrained();
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
        Schema::dropIfExists('edu_program_docs');
    }
};
