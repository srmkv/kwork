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
        Schema::dropIfExists('filters');
        Schema::create('filters', function (Blueprint $table) {
            $table->id();
            $table->integer('tag_id')->nullable()->comment('id тэга фильтрации, он же Фильтр в каталоге');
            $table->integer('category_id')->nullable()->comment('корневая категория');
            $table->integer('sub_category_id')->nullable()->comment('вложенные категории');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filters');
    }
};
