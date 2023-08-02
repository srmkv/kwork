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
        Schema::create('form_data_directors', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('title')->nullable();
            $table->string('director_name')->nullable();
            $table->string('director_position')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('admin_section_spoiler_id')->constrained();

            $table->string('form_type')->default('form_data_directors');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_data_directors');
    }
};
