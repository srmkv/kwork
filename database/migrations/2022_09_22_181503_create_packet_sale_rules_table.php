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
        Schema::create('packet_sale_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('packet_id')->nullable();
            $table->unsignedSmallInteger('count')->nullable();
            $table->float('price')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packet_sale_rules');
    }
};
