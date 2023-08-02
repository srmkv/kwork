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
        Schema::create('form_material_equipment', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title_block')->nullable();
            $table->string('title_position')->nullable();
            $table->text('desc_position')->nullable();

            //not nullable()
            $table->unsignedBigInteger('admin_section_spoiler_id')->constrained(); 
            //custom
            $table->string('form_type')->default('form_material_equipment');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_material_equipment');
    }
};
