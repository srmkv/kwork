<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::dropIfExists('messages');
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->constrained();
            $table->text('message');
            $table->integer('chat_room_id')->nullable();
            $table->timestamps();            
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
