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
        Schema::create('admission_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->constrained();
            $table->unsignedBigInteger('user_id')->constrained();
            $table->string('title')->nullable();
            $table->string('status')->default('consideration')->comment('consideration/approve/error');
            $table->json('need_documents')->nullable()->comment('массив документов, которые входят в этот документ');
            $table->json('user_documents')->nullable()->comment('Документы которые подает пользователь на конкретный адмишен');
            $table->string('type')->nullable();
            $table->string('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admission_documents');
    }
};
