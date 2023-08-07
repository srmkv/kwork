<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Заменяющий документ
     * @return void
     */
    public function up()
    {
        Schema::create('document_course_required_documents', function (Blueprint $table) {
            $table->id();
            $table->integer('course_required_document_id')->constrained()->nullable();
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
        Schema::dropIfExists('document_course_required_documents');
    }
};
