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
        Schema::create('form_international_cooperations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_section_spoiler_id')->constrained();
            $table->string('title_form')->nullable();
            $table->string('name')->nullable();
            $table->integer('media_id')->nullable()->unsigned();            
            $table->timestamps();
            //custom
            $table->string('form_type')->default('form_international_cooperations');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_international_cooperations');
    }
};
