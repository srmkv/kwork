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
        Schema::create('payment_cards', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            // ид карты уник.
            $table->integer('card_id')->nullable();

            // номер карты замаскированный
            $table->string('pan')->nullable();

            // статус карты
            // A — активная
            // I — неактивная
            // D - удаленная
            $table->string('status')->nullable();

            // Идентификатор автоплатежа
            $table->string('rebill_id')->nullable();
            
            // тип карты
            // 0 — списания
            // 1 — пополнения
            // 2 — списания и пополнения
            $table->integer('card_type')->nullable();

            // ключ кастомера
            $table->string('customer_key')->nullable();

            // срок карты 
            $table->integer('exp_date')->nullable(); 
            $table->unsignedBigInteger('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_cards');
    }
};
