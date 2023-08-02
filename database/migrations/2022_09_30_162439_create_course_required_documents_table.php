<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Основной документ
     * @return void
     */
    public function up()
    {
        Schema::create('course_required_documents', function (Blueprint $table) {
            $table->id();
            $table->integer('document_id')->constrained()->nullable();
            $table->integer('course_id')->constrained()->nullable();
            $table->string('description')->constrained()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_required_documents');
    }
};
