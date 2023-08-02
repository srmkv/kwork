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
        Schema::dropIfExists('flows');
        Schema::create('flows', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->date('start')->nullable();
            $table->date('end')->nullable();
            $table->smallInteger('days_after_by')->nullable()->comment('Сколько дней после оплаты будет доступ к курсу');
            $table->unsignedSmallInteger('study_form_id')->nullable();
            $table->unsignedSmallInteger('type_id')->nullable()->comment('Группами / Начало сразу после оплаты');
            $table->unsignedBigInteger('course_id')->nullable();
            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tables_flows');
    }
};
