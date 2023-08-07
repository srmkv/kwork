<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('type_room')->default('order_course');
            $table->json('profiles')->nullable();
            $table->json('author')->nullable();
            $table->string('title')->nullable();
            $table->text('avatar')->nullable();

            $table->integer('business_order_id')->nullable();
            $table->integer('order_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_rooms');
    }
};
