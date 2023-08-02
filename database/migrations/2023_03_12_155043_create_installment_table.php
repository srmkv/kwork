<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->string('installment_type')->nullable();
            $table->integer('total_price')->nullable();
            $table->integer('count_month')->nullable();
            $table->integer('price_month')->nullable();
            $table->integer('price_first_month')->nullable();

            $table->integer('packet_id')->nullable();
            $table->string('installment_code')->nullable();

        });
    }
    
    public function down()
    {
        Schema::dropIfExists('installments');
    }
};
