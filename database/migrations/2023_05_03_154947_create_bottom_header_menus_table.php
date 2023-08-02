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
        Schema::create('bottom_header_menus', function (Blueprint $table) {
            $table->id();
            $table->string('type_user')->nullable();
            $table->string('item_title')->nullable();
            $table->text('item_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bottom_header_menus');
    }
};
