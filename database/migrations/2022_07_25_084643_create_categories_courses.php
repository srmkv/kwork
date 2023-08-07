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
        Schema::dropIfExists('category_course_parent_child');
        Schema::dropIfExists('course_categories');
        Schema::dropIfExists('parent_child');
        Schema::dropIfExists('artifacts');

        

        Schema::create('course_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('page_title')->nullable();
            $table->longText('description')->nullable();
            $table->string('color')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('status')->default(0);

            //seo
            $table->text('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_keywords')->nullable();
            $table->integer('parent_id')->nullable();
            $table->bigInteger('tag_id')->unsigned()->nullable();
            $table->string('tree')->nullable();
            $table->string('main_parent_ids')->nullable();
            $table->timestamps();
            $table->softDeletes(); // уточнить удаление

        });

        Schema::create('category_course_parent_child', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('child_id')->nullable();

            $table->unsignedInteger('parent_id')->nullable();

            $table->unsignedInteger('root_id')->nullable();
            $table->unsignedInteger('tag_id')->nullable();

            $table->string('tree')->nullable();


        
            $table->timestamps();
        });

    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_course_parent_child');
        Schema::dropIfExists('course_categories');
        Schema::dropIfExists('parent_child');
        Schema::dropIfExists('artifacts');
    }
};
