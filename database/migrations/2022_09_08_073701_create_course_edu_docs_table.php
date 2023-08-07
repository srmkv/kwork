<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Заменяющий обр документ
     * @return void
     */
    public function up()
    {
        Schema::create('course_edu_docs_replacement', function (Blueprint $table) {
            $table->id();
            $table->integer('doc_edu_direction_id')->constrained()->nullable();
            $table->integer('document_id')->constrained()->nullable();
            $table->integer('course_id')->constrained()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_edu_docs_replacement');
    }
};
