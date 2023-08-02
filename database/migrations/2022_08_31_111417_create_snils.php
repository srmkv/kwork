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
        Schema::create('snils', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('number_snils')->nullable();
            $table->integer('doc_type_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedSmallInteger('media_id')->nullable();
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('snils');
    }
};
