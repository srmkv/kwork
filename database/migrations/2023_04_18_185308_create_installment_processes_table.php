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
        Schema::create('installment_processes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('order_id')->nullable();
            // NEW - создана/попытка оплаты
            // PART_PAID - частично оплачена
            // CONFIRMED - полностью оплачена
            $table->string('status')->default('NEW');
            $table->unsignedBigInteger('user_id')->constrained()->nullable();
            $table->string('tinkoff_payment_id')->nullable();
            $table->string('tinkoff_order_id')->nullable();
            $table->integer('save_card')->default(0);
            $table->json('body_installment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('installment_processes');
    }
};
