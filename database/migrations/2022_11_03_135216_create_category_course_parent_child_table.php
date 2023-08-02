<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Связь категорий сами с собой ManyToMany
     * Пока что лучше оставить, чтоб дочерние категории не попали ко всем родителям
     * @return void
     */
    public function up()
    {
        // Schema::dropIfExists('parent_child');
        // Schema::dropIfExists('artifacts');
        // Schema::dropIfExists('category_course_parent_child');

        // Schema::create('category_course_parent_child', function (Blueprint $table) {
        //     $table->unsignedInteger('parent_id');
        //     $table->foreign('parent_id')
        //         ->references('id')
        //         ->on('course_categories');
        
        //     $table->unsignedInteger('child_id')->nullable();
        //     $table->foreign('child_id')
        //         ->references('id')
        //         ->on('course_categories');
        
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_course_parent_child');
    }
};
