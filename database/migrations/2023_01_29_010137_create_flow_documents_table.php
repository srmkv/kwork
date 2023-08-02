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
        Schema::create('flow_documents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('course_docs_take_id');
            $table->string('sms')->default(0)->comment('Подписан ли через смс');
            $table->string('moderated')->default(0)->comment('загружен ли на модерацию, 0 - не загружен, 1 - на модерации, 2 - подписан');
            $table->string('type_delivery')->default('pickup')->comment('самовывоз/доставка');
            $table->unsignedBigInteger('order_id');
            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flow_documents');
    }
};
