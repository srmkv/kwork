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
        Schema::create('admin_section_spoilers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->integer('position')->default(1);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('admin_section_id')->constrained()->nullable();
            //custom
            $table->unsignedBigInteger('admin_section_tab_id')->nullable()->constrained();
            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('section_spoilers');
    }
};
