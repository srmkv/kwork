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
        Schema::create('spoiler_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('type_spoiler_form_id');
            $table->unsignedSmallInteger('admin_section_spoiler_id')->constrained();



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spoiler_forms');
    }
};
