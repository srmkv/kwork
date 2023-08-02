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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('order_id')->constrained();
            $table->integer('amount')->nullable()->comment('сумма в копейках');
            $table->string('description')->nullable();    
            $table->unsignedBigInteger('user_id')->nullable();    
            $table->string('user_phone')->nullable()->comment('Участвует в дальнейшем в GetCustomer');    
            $table->string('user_email')->nullable();    
            $table->unsignedBigInteger('pay_method_id')->constrained()->default(1);
            $table->text('payment_url')->nullable()->comment('ссылка на оплату');
            $table->ipAddress('payer_ip')->default('1.1.1.1');
            $table->integer('error_code')->default(0);
            $table->string('payment_status')->nullable();
            $table->integer('save_card')->default(0);   
            $table->integer('installment_process_id')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
