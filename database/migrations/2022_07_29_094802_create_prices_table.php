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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            // $table->timestamps();

            $table->float('old_price', 7, 2)->defautl(0.00);
            $table->float('new_price', 7, 2)->defautl(0.00);
            $table->float('instalment_price', 7, 2)->defautl(0.00);
            $table->float('default_price', 7, 2)->defautl(0.00);

            //скидка ?

            $table->integer('discount')->defautl(1);

            //привязка цены к пакету
            // $table->unsignedBigInteger('packet_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prices');
    }
};
