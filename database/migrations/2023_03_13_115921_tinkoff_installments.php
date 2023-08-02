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
        Schema::create('tinkoff_type_installments', function (Blueprint $table) {
            $table->id();
            $table->string('installment_code')->nullable();
            $table->integer('count_month')->nullable();
            $table->string('discont')->nullable();
            $table->integer('price_first_month')->default(0);
            $table->string('title_full')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
