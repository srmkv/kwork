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
        Schema::create('text_block_who_suited', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('course_id')->constrained()->nullable();
            $table->string('icon_color')->default('#000')->nullable();
            $table->string('icon')->nullable();
            // создать медиабиблиотеку с файлами
            // $table->bigInteger('files_id')->nullable()->unsigned()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('text_block_who_suited');
    }
};
