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
        Schema::create('tax_type', function (Blueprint $table) {
            $table->id();
            // $table->timestamps();
            $table->string('title');
            $table->string('description')->nullable();

            // ОСН — основная система налогообложения
            // УСН — упрощенная система налогообложения
            // ЕСХН — единый сельскохозяйственный налог
            // ПСН — патентная система налогообложения
            // НПД — налог на профессиональный доход

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_type');
    }
};
