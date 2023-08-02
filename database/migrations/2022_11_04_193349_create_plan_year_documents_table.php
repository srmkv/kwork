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
        Schema::create('plan_year_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_plan_year_id')->constrained();
            $table->string('title_doc')->nullable();
            $table->integer('media_id')->nullable()->unsigned();
            $table->string('url')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_year_documents');
    }
};
