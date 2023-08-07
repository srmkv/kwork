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
        Schema::create('form_vacant_places', function (Blueprint $table) {
            $table->id();
            // $table->timestamps();

            $table->unsignedBigInteger('admin_section_spoiler_id')->constrained();
            $table->string('form_type')->default('form_vacant_places');
            $table->string('title')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->string('type_places')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_vacant_places');
    }
};
