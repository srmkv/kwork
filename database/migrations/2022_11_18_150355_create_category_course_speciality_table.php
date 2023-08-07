<?php

use App\Models\Course\CategoryCourseSpecialityAnswer;
use App\Models\Course\CategoryCourseSpecialityFaq;
use App\Models\Course\CategoryCourseSpecialityQuestion;
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
        Schema::create('category_course_speciality', function (Blueprint $table) {
            $table->id();
            $table->integer('category_course_id');
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->integer('level_education_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_course_speciality');
        CategoryCourseSpecialityFaq::truncate();
        CategoryCourseSpecialityAnswer::truncate();
        CategoryCourseSpecialityQuestion::truncate();
    }
};
