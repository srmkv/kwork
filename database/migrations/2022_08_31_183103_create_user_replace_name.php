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
        Schema::create('user_replaces_name', function (Blueprint $table) {
            $table->id();
            
            $table->string('old_name')->nullable();
            $table->string('old_middle_name')->nullable();
            $table->string('old_last_name')->nullable();

            $table->string('new_name')->nullable();
            $table->string('new_middle_name')->nullable();
            $table->string('new_last_name')->nullable();

            $table->unsignedBigInteger('user_id');

            $table->timestamps();
            $table->integer('media_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_replaces_name');
    }
};
