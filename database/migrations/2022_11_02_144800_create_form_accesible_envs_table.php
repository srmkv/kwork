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
        Schema::create('form_accesible_envs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('admin_section_spoiler_id')->constrained();
            $table->string('title')->nullable();

            // $table->integer('media_id')->nullable()->unsigned(); // отдельная коллекция ..

            //custom
            $table->string('form_type')->default('form_accesible_envs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_accesible_envs');
    }
};
