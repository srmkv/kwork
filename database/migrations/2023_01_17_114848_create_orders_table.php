<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{   
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('payment_id')->nullable();
            $table->unsignedSmallInteger('order_status_id')->default(1);
            $table->integer('price')->default(0);
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('flow_id')->nullable();
            $table->unsignedBigInteger('packet_id')->nullable();
            $table->unsignedBigInteger('business_order_id')->nullable();
            $table->unsignedBigInteger('business_chat_id')->nullable();

        });
    }


    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
