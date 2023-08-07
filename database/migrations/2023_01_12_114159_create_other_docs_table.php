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
        Schema::create('other_docs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_id')->comment('тип дополнительного документа, без типа существовать не может , 01.01.23 - правки');
            $table->string('title')->nullable();
            $table->json('status_doc')->nullable();
            $table->unsignedBigInteger('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('other_docs');
    }
};
