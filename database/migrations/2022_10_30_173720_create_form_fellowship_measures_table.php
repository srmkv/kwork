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
        Schema::create('form_fellowship_measures', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('admin_section_spoiler_id')->constrained();
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->integer('count_seats')->default(0);

            //custom
            $table->string('form_type')->default('form_fellowship_measures');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_fellowship_measures');
    }
};
