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
        Schema::dropIfExists('packets');
        Schema::create('packets', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('flow_id')->nullable();
            $table->float('default_price')->nullable();
            $table->float('old_price')->nullable();
            $table->float('instalment_price')->nullable();
            $table->float('instalment_month_price')->nullable();
            $table->string('icon')->nullable();
            $table->string('icon_color')->nullable();
            $table->date('date_sale_end')->nullable();
            $table->time('time_sale_end')->nullable();
            $table->tinyInteger('is_limit_sales_by_date')->nullable();
            $table->unsignedSmallInteger('count_places')->nullable();
            $table->tinyInteger('is_limit_places')->nullable();
            $table->boolean('enable_sale_rules')->default(0);
            $table->timestamps();
            $table->json('split_months')->nullable();
            $table->json('tinkoff_installment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packet');
    }
};
